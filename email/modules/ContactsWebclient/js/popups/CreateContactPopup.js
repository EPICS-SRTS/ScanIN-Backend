'use strict';

var
	_ = require('underscore'),
	$ = require('jquery'),
	ko = require('knockout'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	
	Api = require('%PathToCoreWebclientModule%/js/Api.js'),
	Routing = require('%PathToCoreWebclientModule%/js/Routing.js'),
	Screens = require('%PathToCoreWebclientModule%/js/Screens.js'),
	
	CAbstractPopup = require('%PathToCoreWebclientModule%/js/popups/CAbstractPopup.js'),
	
	LinksUtils = require('modules/%ModuleName%/js/utils/Links.js'),
	
	Ajax = require('modules/%ModuleName%/js/Ajax.js'),
	ContactsCache = require('modules/%ModuleName%/js/Cache.js'),
	
	HeaderItemView = require('modules/%ModuleName%/js/views/HeaderItemView.js')
;

/**
 * @constructor
 */
function CCreateContactPopup()
{
	CAbstractPopup.call(this);
	
	this.displayName = ko.observable('');
	this.email = ko.observable('');
	this.phone = ko.observable('');
	this.address = ko.observable('');
	this.skype = ko.observable('');
	this.facebook = ko.observable('');

	this.focusDisplayName = ko.observable(false);

	this.loading = ko.observable(false);

	this.fCallback = function () {};
}

_.extendOwn(CCreateContactPopup.prototype, CAbstractPopup.prototype);

CCreateContactPopup.prototype.PopupTemplate = '%ModuleName%_CreateContactPopup';

/**
 * @param {string} sName
 * @param {string} sEmail
 * @param {Function} fCallback
 */
CCreateContactPopup.prototype.onOpen = function (sName, sEmail, fCallback)
{
	if (this.displayName() !== sName || this.email() !== sEmail)
	{
		this.displayName(sName);
		this.email(sEmail);
		this.phone('');
		this.address('');
		this.skype('');
		this.facebook('');
	}

	this.fCallback = $.isFunction(fCallback) ? fCallback : function () {};
};

CCreateContactPopup.prototype.onSaveClick = function ()
{
	if (!this.canBeSave())
	{
		Screens.showError(TextUtils.i18n('%MODULENAME%/ERROR_EMAIL_OR_NAME_BLANK'));
	}
	else if (!this.loading())
	{
		var
			oParameters = {
				'PrimaryEmail': Enums.ContactsPrimaryEmail.Personal,
				'FullName': this.displayName(),
				'PersonalEmail': this.email(),
				'PersonalPhone': this.phone(),
				'PersonalAddress': this.address(),
				'Skype': this.skype(),
				'Facebook': this.facebook(),
				'Storage': 'personal'
			}
		;

		this.loading(true);
		Ajax.send('CreateContact', { 'Contact': oParameters }, this.onCreateContactResponse, this);
	}
};


CCreateContactPopup.prototype.cancelPopup = function ()
{
	this.loading(false);
	this.closePopup();
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CCreateContactPopup.prototype.onCreateContactResponse = function (oResponse, oRequest)
{
	var oParameters = oRequest.Parameters;
	
	this.loading(false);

	if (!oResponse.Result)
	{
		Api.showErrorByCode(oResponse, TextUtils.i18n('%MODULENAME%/ERROR_CREATE_CONTACT'));
	}
	else
	{
		Screens.showReport(TextUtils.i18n('%MODULENAME%/REPORT_CONTACT_SUCCESSFULLY_ADDED'));
		ContactsCache.clearInfoAboutEmail(oParameters.Contact.PersonalEmail);
		ContactsCache.getContactsByEmails([oParameters.Contact.PersonalEmail], this.fCallback);
		this.closePopup();
		
		if (!HeaderItemView.isCurrent())
		{
			HeaderItemView.recivedAnim(true);
		}
	}
};

CCreateContactPopup.prototype.canBeSave = function ()
{
	return this.displayName() !== '' || this.email() !== '';
};

CCreateContactPopup.prototype.goToContacts = function ()
{
	ContactsCache.saveNewContactParams({
		displayName: this.displayName(),
		email: this.email(),
		phone: this.phone(),
		address: this.address(),
		skype: this.skype(),
		facebook: this.facebook()
	});
	this.closePopup();
	Routing.replaceHash(LinksUtils.getContacts('personal', '', '', 1, '', 'create-contact'));
};

module.exports = new CCreateContactPopup();
