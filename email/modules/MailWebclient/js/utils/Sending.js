'use strict';

var
	_ = require('underscore'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
	
	Api = require('%PathToCoreWebclientModule%/js/Api.js'),
	App = require('%PathToCoreWebclientModule%/js/App.js'),
	Routing = require('%PathToCoreWebclientModule%/js/Routing.js'),
	Screens = require('%PathToCoreWebclientModule%/js/Screens.js'),
	
	CAddressModel = require('%PathToCoreWebclientModule%/js/models/CAddressModel.js'),
	CAddressListModel = require('%PathToCoreWebclientModule%/js/models/CAddressListModel.js'),
	
	MessageUtils = require('modules/%ModuleName%/js/utils/Message.js'),
	
	AccountList = require('modules/%ModuleName%/js/AccountList.js'),
	Ajax = require('modules/%ModuleName%/js/Ajax.js'),
	MailCache = require('modules/%ModuleName%/js/Cache.js'),
	Settings = require('modules/%ModuleName%/js/Settings.js'),
	
	MainTab = App.isNewTab() && window.opener && window.opener.MainTabMailMethods,
	
	SendingUtils = {
		sReplyText: '',
		sReplyDraftUid: '',
		oPostponedMailData: null
	}
;

/**
 * @param {string} sText
 * @param {string} sDraftUid
 */
SendingUtils.setReplyData = function (sText, sDraftUid)
{
	this.sReplyText = sText;
	this.sReplyDraftUid = sDraftUid;
};

/**
 * @param {string} sMethod
 * @param {Object} oParameters
 * @param {boolean} bShowLoading
 * @param {Function} fSendMessageResponseHandler
 * @param {Object} oSendMessageResponseContext
 * @param {boolean=} bPostponedSending = false
 */
SendingUtils.send = function (sMethod, oParameters, bShowLoading, fSendMessageResponseHandler, oSendMessageResponseContext, bPostponedSending)
{
	var
		iAccountID = oParameters.AccountID,
		oAccount = AccountList.getAccount(iAccountID),
		oFolderList = MailCache.oFolderListItems[iAccountID],
		sLoadingMessage = '',
		sSentFolder = oFolderList ? oFolderList.sentFolderFullName() : '',
		sDraftFolder = oFolderList ? oFolderList.draftsFolderFullName() : '',
		sCurrEmail = oAccount ? oAccount.email() : '',
		bSelfRecipient = (oParameters.To.indexOf(sCurrEmail) > -1 || oParameters.Cc.indexOf(sCurrEmail) > -1 || 
			oParameters.Bcc.indexOf(sCurrEmail) > -1)
	;
	
	if (oAccount.bSaveRepliesToCurrFolder && !bSelfRecipient && Types.isNonEmptyArray(oParameters.DraftInfo, 3))
	{
		sSentFolder = oParameters.DraftInfo[2];
	}
	
	oParameters.Method = sMethod;
	oParameters.ShowReport = bShowLoading;
	
	switch (sMethod)
	{
		case 'SendMessage':
			sLoadingMessage = TextUtils.i18n('COREWEBCLIENT/INFO_SENDING');
			oParameters.SentFolder = sSentFolder;
			if (oParameters.DraftUid !== '')
			{
				oParameters.DraftFolder = sDraftFolder;
				if (MainTab)
				{
					MainTab.removeOneMessageFromCacheForFolder(oParameters.AccountID, oParameters.DraftFolder, oParameters.DraftUid);
					MainTab.replaceHashWithoutMessageUid(oParameters.DraftUid);
				}
				else
				{
					MailCache.removeOneMessageFromCacheForFolder(oParameters.AccountID, oParameters.DraftFolder, oParameters.DraftUid);
					Routing.replaceHashWithoutMessageUid(oParameters.DraftUid);
				}
			}
			break;
		case 'SaveMessage':
			sLoadingMessage = TextUtils.i18n('%MODULENAME%/INFO_SAVING');
			if (typeof oParameters.DraftFolder === 'undefined')
			{
				oParameters.DraftFolder = sDraftFolder;
			}
			
			// Message with this uid will not be selected from message list
			MailCache.savingDraftUid(oParameters.DraftUid);
			if (MainTab)
			{
				MainTab.startMessagesLoadingWhenDraftSaving(oParameters.AccountID, oParameters.DraftFolder);
				MainTab.replaceHashWithoutMessageUid(oParameters.DraftUid);
			}
			else
			{
				MailCache.startMessagesLoadingWhenDraftSaving(oParameters.AccountID, oParameters.DraftFolder);
				Routing.replaceHashWithoutMessageUid(oParameters.DraftUid);
			}
			break;
	}
	
	if (bShowLoading)
	{
		Screens.showLoading(sLoadingMessage);
	}
	
	if (bPostponedSending)
	{
		this.postponedMailData = {
			'Parameters': oParameters,
			'SendMessageResponseHandler': fSendMessageResponseHandler,
			'SendMessageResponseContext': oSendMessageResponseContext
		};
	}
	else
	{
		Ajax.send(sMethod, oParameters, fSendMessageResponseHandler, oSendMessageResponseContext);
	}
};

/**
 * @param {string} sDraftUid
 */
SendingUtils.sendPostponedMail = function (sDraftUid)
{
	var
		oData = this.postponedMailData,
		oParameters = oData.Parameters,
		iAccountID = oParameters.AccountID,
		oFolderList = MailCache.oFolderListItems[iAccountID],
		sDraftFolder = oFolderList ? oFolderList.draftsFolderFullName() : ''
	;
	
	if (sDraftUid !== '')
	{
		oParameters.DraftUid = sDraftUid;
		oParameters.DraftFolder = sDraftFolder;
		if (MainTab)
		{
			MainTab.removeOneMessageFromCacheForFolder(oParameters.AccountID, oParameters.DraftFolder, oParameters.DraftUid);
			MainTab.replaceHashWithoutMessageUid(oParameters.DraftUid);
		}
		else
		{
			MailCache.removeOneMessageFromCacheForFolder(oParameters.AccountID, oParameters.DraftFolder, oParameters.DraftUid);
			Routing.replaceHashWithoutMessageUid(oParameters.DraftUid);
		}
	}
	
	if (this.postponedMailData)
	{
		Ajax.send(oParameters.Method, oParameters, oData.SendMessageResponseHandler, oData.SendMessageResponseContext);
		this.postponedMailData = null;
	}
};

/**
 * @param {string} sMethod
 * @param {string} sText
 * @param {string} sDraftUid
 * @param {Function} fSendMessageResponseHandler
 * @param {Object} oSendMessageResponseContext
 * @param {boolean} bRequiresPostponedSending
 */
SendingUtils.sendReplyMessage = function (sMethod, sText, sDraftUid, fSendMessageResponseHandler, 
														oSendMessageResponseContext, bRequiresPostponedSending)
{
	var
		oParameters = null,
		oMessage = MailCache.currentMessage(),
		aRecipients = [],
		oFetcherOrIdentity = null
	;

	if (oMessage)
	{
		aRecipients = oMessage.oTo.aCollection.concat(oMessage.oCc.aCollection);
		oFetcherOrIdentity = this.getFirstFetcherOrIdentityByRecipientsOrDefault(aRecipients, oMessage.accountId());

		oParameters = this.getReplyDataFromMessage(oMessage, Enums.ReplyType.ReplyAll, oMessage.accountId(), oFetcherOrIdentity, false, sText, sDraftUid);

		oParameters.AccountID = oMessage.accountId();

		if (oFetcherOrIdentity)
		{
			oParameters.FetcherID = oFetcherOrIdentity && oFetcherOrIdentity.FETCHER ? oFetcherOrIdentity.id() : '';
			oParameters.IdentityID = oFetcherOrIdentity && !oFetcherOrIdentity.FETCHER ? oFetcherOrIdentity.id() : '';
		}

		oParameters.Bcc = '';
		oParameters.Importance = Enums.Importance.Normal;
		oParameters.SendReadingConfirmation = false;
		oParameters.IsQuickReply = true;
		oParameters.IsHtml = true;

		oParameters.Attachments = this.convertAttachmentsForSending(oParameters.Attachments);

		this.send(sMethod, oParameters, false, fSendMessageResponseHandler, oSendMessageResponseContext, bRequiresPostponedSending);
	}
};

/**
 * @param {Array} aAttachments
 * 
 * @return {Object}
 */
SendingUtils.convertAttachmentsForSending = function (aAttachments)
{
	var oAttachments = {};
	
	_.each(aAttachments, function (oAttach) {
		oAttachments[oAttach.tempName()] = [
			oAttach.fileName(),
			oAttach.linked() ? oAttach.cid() : '',
			oAttach.inline() ? '1' : '0',
			oAttach.linked() ? '1' : '0',
			oAttach.contentLocation()
		];
	});
	
	return oAttachments;
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 * @param {boolean} bRequiresPostponedSending
 * 
 * @return {Object}
 */
SendingUtils.onSendOrSaveMessageResponse = function (oResponse, oRequest, bRequiresPostponedSending)
{
	var
		oParameters = oRequest.Parameters,
		bResult = !!oResponse.Result,
		sFullName, sUid, sReplyType
	;

	if (!bRequiresPostponedSending)
	{
		Screens.hideLoading();
	}
	
	switch (oRequest.Method)
	{
		case 'SaveMessage':
			// All messages can not be selected from message list if message saving is done
			MailCache.savingDraftUid('');
			if (!bResult)
			{
				if (oParameters.ShowReport)
				{
					if (-1 !== $.inArray(oRequest.Parameters.DraftFolder, MailCache.getCurrentTemplateFolders()))
					{
						Api.showErrorByCode(oResponse, TextUtils.i18n('%MODULENAME%/ERROR_TEMPLATE_SAVING'));
					}
					else
					{
						Api.showErrorByCode(oResponse, TextUtils.i18n('%MODULENAME%/ERROR_MESSAGE_SAVING'));
					}
				}
			}
			else
			{
				if (oParameters.ShowReport && !bRequiresPostponedSending)
				{
					if (-1 !== $.inArray(oRequest.Parameters.DraftFolder, MailCache.getCurrentTemplateFolders()))
					{
						Screens.showReport(TextUtils.i18n('%MODULENAME%/REPORT_TEMPLATE_SAVED'));
					}
					else
					{
						Screens.showReport(TextUtils.i18n('%MODULENAME%/REPORT_MESSAGE_SAVED'));
					}
				}

				if (!oResponse.Result.NewUid)
				{
					Settings.AllowAutosaveInDrafts = false;
				}
			}
			break;
		case 'SendMessage':
			if (!bResult)
			{
				Api.showErrorByCode(oResponse, TextUtils.i18n('%MODULENAME%/ERROR_MESSAGE_SENDING'));
			}
			else
			{
				if (oParameters.IsQuickReply)
				{
					Screens.showReport(TextUtils.i18n('%MODULENAME%/REPORT_MESSAGE_SENT'));
				}
				else
				{
					if (MainTab)
					{
						MainTab.showReport(TextUtils.i18n('%MODULENAME%/REPORT_MESSAGE_SENT'));
					}
					else
					{
						Screens.showReport(TextUtils.i18n('%MODULENAME%/REPORT_MESSAGE_SENT'));
					}
				}

				if (_.isArray(oParameters.DraftInfo) && oParameters.DraftInfo.length === 3)
				{
					sReplyType = oParameters.DraftInfo[0];
					sUid = oParameters.DraftInfo[1];
					sFullName = oParameters.DraftInfo[2];
					MailCache.markMessageReplied(oParameters.AccountID, sFullName, sUid, sReplyType);
				}
			}
			
			if (oParameters.SentFolder)
			{
				if (MainTab)
				{
					MainTab.removeMessagesFromCacheForFolder(oParameters.AccountID, oParameters.SentFolder);
				}
				else
				{
					MailCache.removeMessagesFromCacheForFolder(oParameters.AccountID, oParameters.SentFolder);
				}
			}
			
			break;
	}

	if (oParameters.DraftFolder && !bRequiresPostponedSending)
	{
		if (MainTab)
		{
			MainTab.removeMessagesFromCacheForFolder(oParameters.AccountID, oParameters.DraftFolder);
		}
		else
		{
			MailCache.removeMessagesFromCacheForFolder(oParameters.AccountID, oParameters.DraftFolder);
		}
	}
	
	return {Method: oRequest.Method, Result: bResult, NewUid: oResponse.Result ? oResponse.Result.NewUid : ''};
};

/**
 * @param {Object} oMessage
 * @param {string} sReplyType
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * @param {boolean} bPasteSignatureAnchor
 * @param {string} sText
 * @param {string} sDraftUid
 * 
 * @return {Object}
 */
SendingUtils.getReplyDataFromMessage = function (oMessage, sReplyType, iAccountId,
													oFetcherOrIdentity, bPasteSignatureAnchor, sText, sDraftUid)
{
	var
		oReplyData = {
			DraftInfo: [],
			DraftUid: '',
			To: '',
			Cc: '',
			Bcc: '',
			Subject: '',
			Attachments: [],
			InReplyTo: oMessage.messageId(),
			References: this.getReplyReferences(oMessage)
		},
		aAttachmentsLink = [],
		sToAddr = oMessage.oReplyTo.getFull(),
		sTo = oMessage.oTo.getFull()
	;
	
	if (sToAddr === '' || oMessage.oFrom.getFirstEmail() === oMessage.oReplyTo.getFirstEmail() && oMessage.oReplyTo.getFirstName() === '')
	{
		sToAddr = oMessage.oFrom.getFull();
	}
	
	if (!sText || sText === '')
	{
		sText = this.sReplyText;
		this.sReplyText = '';
	}
	
	if (sReplyType === 'forward')
	{
		oReplyData.Text = sText + this.getForwardMessageBody(oMessage, iAccountId, oFetcherOrIdentity);
	}
	else if (sReplyType === 'resend')
	{
		oReplyData.Text = oMessage.getConvertedHtml();
		oReplyData.Cc = oMessage.cc();
		oReplyData.Bcc = oMessage.bcc();
	}
	else
	{
		oReplyData.Text = sText + GetReplyMessageBody.call(this, oMessage, iAccountId, oFetcherOrIdentity, bPasteSignatureAnchor);
	}
	
	if (sDraftUid)
	{
		oReplyData.DraftUid = sDraftUid;
	}
	else
	{
		oReplyData.DraftUid = this.sReplyDraftUid;
		this.sReplyDraftUid = '';
	}

	switch (sReplyType)
	{
		case Enums.ReplyType.Reply:
			oReplyData.DraftInfo = [Enums.ReplyType.Reply, oMessage.uid(), oMessage.folder()];
			oReplyData.To = sToAddr;
			oReplyData.Subject = this.getReplySubject(oMessage.subject(), true);
			aAttachmentsLink = _.filter(oMessage.attachments(), function (oAttach) {
				return oAttach.linked();
			});
			break;
		case Enums.ReplyType.ReplyAll:
			oReplyData.DraftInfo = [Enums.ReplyType.ReplyAll, oMessage.uid(), oMessage.folder()];
			oReplyData.To = sToAddr;
			oReplyData.Cc = GetReplyAllCcAddr(oMessage, iAccountId, oFetcherOrIdentity);
			oReplyData.Subject = this.getReplySubject(oMessage.subject(), true);
			aAttachmentsLink = _.filter(oMessage.attachments(), function (oAttach) {
				return oAttach.linked();
			});
			break;
		case Enums.ReplyType.Resend:
			oReplyData.DraftInfo = [Enums.ReplyType.Resend, oMessage.uid(), oMessage.folder(), oMessage.cc(), oMessage.bcc()];
			oReplyData.To = sTo;
			oReplyData.Subject = oMessage.subject();
			aAttachmentsLink = oMessage.attachments();
			break;
		case Enums.ReplyType.ForwardAsAttach:
		case Enums.ReplyType.Forward:
			oReplyData.DraftInfo = [Enums.ReplyType.Forward, oMessage.uid(), oMessage.folder()];
			oReplyData.Subject = this.getReplySubject(oMessage.subject(), false);
			aAttachmentsLink = oMessage.attachments();
			break;
	}
	
	_.each(aAttachmentsLink, function (oAttachLink) {
		if (oAttachLink.getCopy)
		{
			var oCopy = oAttachLink.getCopy();
			oReplyData.Attachments.push(oCopy);
		}
	});

	return oReplyData;
};

/**
 * Prepares and returns references for reply message.
 *
 * @param {Object} oMessage
 * 
 * @return {string}
 */
SendingUtils.getReplyReferences = function (oMessage)
{
	var
		sRef = oMessage.references(),
		sInR = oMessage.messageId(),
		sPos = sRef.indexOf(sInR)
	;

	if (sPos === -1)
	{
		sRef += ' ' + sInR;
	}

	return sRef;
};

/**
 * @param {Object} oMessage
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * @param {boolean} bPasteSignatureAnchor
 * 
 * @return {string}
 */
function GetReplyMessageBody(oMessage, iAccountId, oFetcherOrIdentity, bPasteSignatureAnchor)
{
	var
		sReplyTitle = TextUtils.i18n('%MODULENAME%/TEXT_REPLY_MESSAGE', {
			'DATE': oMessage.oDateModel.getDate(),
			'TIME': oMessage.oDateModel.getTime(),
			'SENDER': TextUtils.encodeHtml(oMessage.oFrom.getFull())
		}),
		sReplyBody = '<br /><br />' + this.getSignatureText(iAccountId, oFetcherOrIdentity, bPasteSignatureAnchor) + '<br /><br />' +
			'<div data-anchor="reply-title">' + sReplyTitle + '</div><blockquote>' + oMessage.getConvertedHtml() + '</blockquote>'
	;

	return sReplyBody;
}

/**
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * 
 * @return {string}
 */
SendingUtils.getClearSignature = function (iAccountId, oFetcherOrIdentity)
{
	var
		oAccount = AccountList.getAccount(iAccountId),
		sSignature = ''
	;

	if (oFetcherOrIdentity && oFetcherOrIdentity.accountId() === iAccountId && oFetcherOrIdentity.useSignature())
	{
		sSignature = oFetcherOrIdentity.signature();
	}
	else if (oAccount && oAccount.useSignature())
	{
		sSignature = oAccount.signature();
	}

	return sSignature;
};

/**
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * @param {boolean} bPasteSignatureAnchor
 * 
 * @return {string}
 */
SendingUtils.getSignatureText = function (iAccountId, oFetcherOrIdentity, bPasteSignatureAnchor)
{
	var sSignature = this.getClearSignature(iAccountId, oFetcherOrIdentity);

	if (bPasteSignatureAnchor)
	{
		return '<div data-anchor="signature">' + sSignature + '</div>';
	}

	return '<div>' + sSignature + '</div>';
};

/**
 * @param {Array} aRecipients
 * @param {number} iAccountId
 * 
 * @return Object
 */
SendingUtils.getFirstFetcherOrIdentityByRecipientsOrDefault = function (aRecipients, iAccountId)
{
	var
		oAccount = AccountList.getAccount(iAccountId),
		aList = this.getAccountFetchersIdentitiesList(oAccount),
		aEqualEmailList = [],
		oFoundFetcherOrIdentity = null
	;

	_.each(aRecipients, function (oAddr) {
		if (!oFoundFetcherOrIdentity)
		{
			aEqualEmailList = _.filter(aList, function (oItem) {
				return oAddr.sEmail === oItem.email;
			});
			
			switch (aEqualEmailList.length)
			{
				case 0:
					break;
				case 1:
					oFoundFetcherOrIdentity = aEqualEmailList[0];
					break;
				default:
					oFoundFetcherOrIdentity = _.find(aEqualEmailList, function (oItem) {
						return oAddr.sEmail === oItem.email && oAddr.sName === oItem.name;
					});
					
					if (!oFoundFetcherOrIdentity)
					{
						oFoundFetcherOrIdentity = _.find(aEqualEmailList, function (oItem) {
							return oItem.isDefault;
						});
						if (!oFoundFetcherOrIdentity)
						{
							oFoundFetcherOrIdentity = aEqualEmailList[0];
						}
					}
					break;
			}
		}
	});
	
	if (!oFoundFetcherOrIdentity)
	{
		oFoundFetcherOrIdentity = _.find(aList, function (oItem) {
			return oItem.isDefault;
		});
	}
	
	return oFoundFetcherOrIdentity && oFoundFetcherOrIdentity.result;
};

/**
 * @param {Object} oAccount
 * @returns {Array}
 */
SendingUtils.getAccountFetchersIdentitiesList = function (oAccount)
{
	var aList = [];
	
	if (oAccount)
	{
		_.each(oAccount.fetchers(), function (oFetcher) {
			aList.push({
				'email': oFetcher.email(),
				'name': oFetcher.userName(),
				'isDefault': false,
				'result': oFetcher
			});
		});
		
		_.each(oAccount.identities(), function (oIdnt) {
			aList.push({
				'email': oIdnt.email(),
				'name': oIdnt.friendlyName(),
				'isDefault': oIdnt.isDefault(),
				'result': oIdnt
			});
		});
	}

	return aList;
};

/**
 * @param {Object} oMessage
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * 
 * @return {string}
 */
SendingUtils.getForwardMessageBody = function (oMessage, iAccountId, oFetcherOrIdentity)
{
	var
		sCcAddr = TextUtils.encodeHtml(oMessage.oCc.getFull()),
		sCcPart = (sCcAddr !== '') ? TextUtils.i18n('%MODULENAME%/TEXT_FORWARD_MESSAGE_CCPART', {'CCADDR': sCcAddr}) : '',
		sForwardTitle = TextUtils.i18n('%MODULENAME%/TEXT_FORWARD_MESSAGE', {
			'FROMADDR': TextUtils.encodeHtml(oMessage.oFrom.getFull()),
			'TOADDR': TextUtils.encodeHtml(oMessage.oTo.getFull()),
			'CCPART': sCcPart,
			'FULLDATE': oMessage.oDateModel.getFullDate(),
			'SUBJECT': TextUtils.encodeHtml(oMessage.subject())
		}),
		sForwardBody = '<br /><br />' + this.getSignatureText(iAccountId, oFetcherOrIdentity, true) + '<br /><br />' + 
			'<div data-anchor="reply-title">' + sForwardTitle + '</div><br /><br />' + oMessage.getConvertedHtml()
	;

	return sForwardBody;
};

SendingUtils.hasReplyAllCcAddrs = function (oMessage)
{
	var
		iAccountId = oMessage.accountId(),
		aRecipients = oMessage.oTo.aCollection.concat(oMessage.oCc.aCollection),
		oFetcherOrIdentity = this.getFirstFetcherOrIdentityByRecipientsOrDefault(aRecipients, oMessage.accountId()),
		sCcAddrs = GetReplyAllCcAddr(oMessage, iAccountId, oFetcherOrIdentity)
	;
	return sCcAddrs !== '';
};

/**
 * Prepares and returns cc address for reply message.
 *
 * @param {Object} oMessage
 * @param {number} iAccountId
 * @param {Object} oFetcherOrIdentity
 * 
 * @return {string}
 */
function GetReplyAllCcAddr(oMessage, iAccountId, oFetcherOrIdentity)
{
	var
		oAddressList = new CAddressListModel(),
		aAddrCollection = _.union(oMessage.oTo.aCollection, oMessage.oCc.aCollection, 
			oMessage.oBcc.aCollection),
		oCurrAccount = _.find(AccountList.collection(), function (oAccount) {
			return oAccount.id() === iAccountId;
		}, this),
		oCurrAccAddress = new CAddressModel(),
		oFetcherAddress = new CAddressModel()
	;

	oCurrAccAddress.sEmail = oCurrAccount.email();
	oFetcherAddress.sEmail = oFetcherOrIdentity ? oFetcherOrIdentity.email() : '';
	oAddressList.addCollection(aAddrCollection);
	oAddressList.excludeCollection(_.union(oMessage.oFrom.aCollection, [oCurrAccAddress, oFetcherAddress]));

	return oAddressList.getFull();
}

/**
 * Obtains a subject of the message, which is the answer (reply or forward):
 * - adds the prefix "Re" of "Fwd" if the language is English, otherwise - their translation
 * - joins "Re" and "Fwd" prefixes if it is allowed for application in settings
 * 
 * @param {string} sSubject Subject of the message, the answer to which is composed
 * @param {boolean} bReply If **true** the prefix will be "Re", otherwise - "Fwd"
 *
 * @return {string}
 */
SendingUtils.getReplySubject = function (sSubject, bReply)
{
	var
		sRePrefix = TextUtils.i18n('%MODULENAME%/TEXT_REPLY_PREFIX'),
		sFwdPrefix = TextUtils.i18n('%MODULENAME%/TEXT_FORWARD_PREFIX'),
		sPrefix = bReply ? sRePrefix : sFwdPrefix,
		sReSubject = sPrefix + ': ' + sSubject
	;
	
	if (Settings.JoinReplyPrefixes)
	{
		sReSubject = MessageUtils.joinReplyPrefixesInSubject(sReSubject, sRePrefix, sFwdPrefix);
	}
	
	return sReSubject;
};

/**
 * @param {string} sPlain
 * 
 * @return {string}
 */
SendingUtils.getHtmlFromText = function (sPlain)
{
	return sPlain
		.replace(/&/g, '&amp;').replace(/>/g, '&gt;').replace(/</g, '&lt;')
		.replace(/\r/g, '').replace(/\n/g, '<br />')
	;
};

module.exports = SendingUtils;
