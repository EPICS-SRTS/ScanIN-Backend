'use strict';

var
	_ = require('underscore'),
	$ = require('jquery'),
	ko = require('knockout'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
	
	App = require('%PathToCoreWebclientModule%/js/App.js'),
	WindowOpener = require('%PathToCoreWebclientModule%/js/WindowOpener.js'),
	
	CAbstractFileModel = require('%PathToCoreWebclientModule%/js/models/CAbstractFileModel.js'),
	
	Ajax = require('modules/%ModuleName%/js/Ajax.js')
;

/**
 * @constructor
 * @extends CCommonFileModel
 */
function CAttachmentModel()
{
	this.folderName = ko.observable('');
	this.messageUid = ko.observable('');
	
	this.cid = ko.observable('');
	this.contentLocation = ko.observable('');
	this.inline = ko.observable(false);
	this.linked = ko.observable(false);
	this.mimePartIndex = ko.observable('');

	this.messagePart = ko.observable(null);
	
	CAbstractFileModel.call(this);
	
	this.isMessageType = ko.computed(function () {
		this.mimeType();
		this.mimePartIndex();
		return (this.mimeType() === 'message/rfc822');
	}, this);
}

_.extendOwn(CAttachmentModel.prototype, CAbstractFileModel.prototype);

CAttachmentModel.prototype.getNewInstance = function ()
{
	return new CAttachmentModel();
};

CAttachmentModel.prototype.getCopy = function ()
{
	var oCopy = new CAttachmentModel();
	
	oCopy.copyProperties(this);
	
	return oCopy;
};

CAttachmentModel.prototype.copyProperties = function (oSource)
{
	this.fileName(oSource.fileName());
	this.tempName(oSource.tempName());
	this.size(oSource.size());
	this.hash(oSource.hash());
	this.mimeType(oSource.mimeType());
	this.cid(oSource.cid());
	this.contentLocation(oSource.contentLocation());
	this.inline(oSource.inline());
	this.linked(oSource.linked());
	this.thumbnailSrc(oSource.thumbnailSrc());
	this.thumbnailLoaded(oSource.thumbnailLoaded());
	this.statusText(oSource.statusText());
	this.uploaded(oSource.uploaded());
	this.oActionsData = oSource.oActionsData;
	this.actions(oSource.actions());
	this.thumbUrlInQueue(oSource.thumbUrlInQueue());
};

/**
 * Parses attachment data from server.
 *
 * @param {AjaxAttachmenResponse} oData
 */
CAttachmentModel.prototype.additionalParse = function (oData)
{
	this.mimePartIndex(Types.pString(oData.MimePartIndex));

	this.cid(Types.pString(oData.CID));
	this.contentLocation(Types.pString(oData.ContentLocation));
	this.inline(!!oData.IsInline);
	this.linked(!!oData.IsLinked);
};

/**
 * @param {string} sFolderName
 * @param {string} sMessageUid
 */
CAttachmentModel.prototype.setMessageData = function (sFolderName, sMessageUid)
{
	this.folderName(sFolderName);
	this.messageUid(sMessageUid);
};

/**
 * @param {Object} oResult
 * @param {Object} oRequest
 */
CAttachmentModel.prototype.onGetMessageResponse = function (oResult, oRequest)
{
	var
		oParameters = oRequest.Parameters,
		oResult = oResult.Result,
		CMessageModel = require('modules/%ModuleName%/js/models/CMessageModel.js'),
		oMessage = new CMessageModel()
	;
	
	if (oResult && this.oNewWindow)
	{
		oMessage.parse(oResult, oParameters.AccountID, false, true);
		this.messagePart(oMessage);
		this.messagePart().viewMessage(this.oNewWindow);
		this.oNewWindow = undefined;
	}
};

/**
 * Starts viewing attachment on click.
 */
CAttachmentModel.prototype.viewFile = function ()
{
	if (this.isMessageType())
	{
		this.viewMessageFile();
	}
	else
	{
		this.viewCommonFile();
	}
};

/**
 * Starts viewing attachment on click.
 */
CAttachmentModel.prototype.viewMessageFile = function ()
{
	var
		oWin = null,
		sLoadingText = '<div style="margin: 30px; text-align: center; font: normal 14px Tahoma;">' + 
			TextUtils.i18n('COREWEBCLIENT/INFO_LOADING') + '</div>'
	;
	
	oWin = WindowOpener.open('', this.fileName());
	if (oWin)
	{
		if (this.messagePart())
		{
			this.messagePart().viewMessage(oWin);
		}
		else
		{
			$(oWin.document.body).html(sLoadingText);
			this.oNewWindow = oWin;

			Ajax.send('GetMessage', {
				'Folder': this.folderName(),
				'Uid': this.messageUid(),
				'Rfc822MimeIndex': this.mimePartIndex()
			}, this.onGetMessageResponse, this);
		}
		
		oWin.focus();
	}
};

/**
 * @param {Object} oResponse
 * @param {string} sFileUid
 */
CAttachmentModel.prototype.fillDataAfterUploadComplete = function (oResponse, sFileUid)
{
	this.cid(Types.pString(sFileUid));
	if (oResponse && oResponse.Result && oResponse.Result.Attachment)
	{
		this.tempName(Types.pString(oResponse.Result.Attachment.TempName));
		this.mimeType(Types.pString(oResponse.Result.Attachment.MimeType));
		this.size(Types.pInt(oResponse.Result.Attachment.Size));
		this.hash(Types.pString(oResponse.Result.Attachment.Hash));
		this.parseActions(oResponse.Result.Attachment);
	}
};

/**
 * Parses contact attachment data from server.
 *
 * @param {Object} oData
 * @param {string} sMessageFolder
 * @param {string} sMessageUid
 */
CAttachmentModel.prototype.parseFromUpload = function (oData, sMessageFolder, sMessageUid)
{
	this.setMessageData(sMessageFolder, sMessageUid);

	this.fileName(Types.pString(oData.Name));
	this.tempName(oData.TempName ? Types.pString(oData.TempName) : this.fileName());
	this.mimeType(Types.pString(oData.MimeType));
	this.size(Types.pInt(oData.Size));

	this.hash(Types.pString(oData.Hash));
	this.parseActions(oData);

	this.uploadUid(this.hash());
	this.uploaded(true);
	
	this.uploadStarted(false);
};

CAttachmentModel.prototype.parseActions = function (oData)
{
	this.thumbUrlInQueue(Types.pString(oData.ThumbnailUrl) !== '' ? Types.pString(oData.ThumbnailUrl) + '/' + Math.random() : '');
	this.commonParseActions(oData);
	
	if (this.isMessageType())
	{
		if (this.folderName() !== '' && this.messageUid() !== '')
		{
			if (!this.hasAction('view'))
			{
				this.actions.unshift('view');
			}
			this.otherTemplates.push({
				name: '%ModuleName%_PrintMessageView',
				data: this.messagePart
			});
		}
		else
		{
			this.actions(_.without(this.actions(), 'view'));
		}
	}
	else
	{
		this.commonExcludeActions();
	}
	
	App.broadcastEvent('%ModuleName%::ParseFile::after', this);
};

CAttachmentModel.prototype.errorFromUpload = function ()
{
	this.uploaded(true);
	this.uploadError(true);
	this.uploadStarted(false);
	this.statusText(TextUtils.i18n('COREWEBCLIENT/ERROR_UPLOAD_UNKNOWN'));
};

module.exports = CAttachmentModel;
