'use strict';

var
	_ = require('underscore'),
	$ = require('jquery'),
	ko = require('knockout'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	
	ModulesManager = require('%PathToCoreWebclientModule%/js/ModulesManager.js'),
	ComposeMessageWithAttachments = ModulesManager.run('MailWebclient', 'getComposeMessageWithAttachments'),
	Ajax = require('%PathToCoreWebclientModule%/js/Ajax.js'),
	
	CAbstractPopup = require('%PathToCoreWebclientModule%/js/popups/CAbstractPopup.js')
;

/**
 * @constructor
 */
function CShowKeyArmorPopup()
{
	CAbstractPopup.call(this);
	
	this.bAllowSendEmails = _.isFunction(ComposeMessageWithAttachments);
	
	this.armor = ko.observable('');
	this.htmlArmor = ko.computed(function () {
		return TextUtils.encodeHtml(this.armor().replace(/\r/g, ''));
	}, this);
	this.user = ko.observable('');
	this.private = ko.observable(false);
	this.popupHeading = ko.computed(function () {
		return this.private() ?
			TextUtils.i18n('%MODULENAME%/HEADING_VIEW_PRIVATE_KEY', {'USER': this.user()}) :
			TextUtils.i18n('%MODULENAME%/HEADING_VIEW_PUBLIC_KEY', {'USER': this.user()});
	}, this);
	
	this.downloadLinkHref = ko.computed(function() {
		var
			sHref = '#',
			oBlob = null
		;
		
		if (Blob && window.URL && $.isFunction(window.URL.createObjectURL))
		{
			oBlob = new Blob([this.armor()], {type: 'text/plain'});
			sHref = window.URL.createObjectURL(oBlob);
		}
		
		return sHref;
	}, this);
	
	this.downloadLinkFilename = ko.computed(function () {
		var
			sConvertedUser = this.user().replace(/</g, '').replace(/>/g, ''),
			sLangKey = this.private() ? '%MODULENAME%/TEXT_PRIVATE_KEY_FILENAME' : '%MODULENAME%/TEXT_PUBLIC_KEY_FILENAME'
		;
		return TextUtils.i18n(sLangKey, {'USER': sConvertedUser}) + '.asc';
	}, this);
	
	this.domKey = ko.observable(null);
}

_.extendOwn(CShowKeyArmorPopup.prototype, CAbstractPopup.prototype);

CShowKeyArmorPopup.prototype.PopupTemplate = '%ModuleName%_ShowKeyArmorPopup';

/**
 * @param {Object} oKey
 */
CShowKeyArmorPopup.prototype.onOpen = function (oKey)
{
	this.armor(oKey.getArmor());
	this.user(oKey.getUser());
	this.private(oKey.isPrivate());
};

CShowKeyArmorPopup.prototype.send = function ()
{
	if (this.bAllowSendEmails && this.armor() !== '' && this.downloadLinkFilename() !== '')
	{
		Ajax.send('Core', 'SaveContentAsTempFile', { 'Content': this.armor(), 'FileName': this.downloadLinkFilename() }, function (oResponse) {
			if (oResponse.Result)
			{
				ComposeMessageWithAttachments([oResponse.Result]);
				this.closePopup();
			}
		}, this);
	}
};

CShowKeyArmorPopup.prototype.select = function ()
{
	var
		oDomKey = (this.domKey() && this.domKey().length === 1) ? this.domKey()[0] : null,
		oSel = null,
		oRange = null
	;
	
	if (oDomKey && window.getSelection && document.createRange)
	{
		oRange = document.createRange();
		oRange.setStart(oDomKey, 0);
		oRange.setEnd(oDomKey, 1);
		oSel = window.getSelection();
		oSel.removeAllRanges();
		oSel.addRange(oRange);
	}
};

module.exports = new CShowKeyArmorPopup();
