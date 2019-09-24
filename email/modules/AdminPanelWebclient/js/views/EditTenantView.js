'use strict';

var
	ko = require('knockout'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js')
;

/**
 * @constructor
 */
function CEditTenantView()
{
	this.sHeading = TextUtils.i18n('%MODULENAME%/HEADING_CREATE_TENANT');
	this.id = ko.observable(0);
	this.name = ko.observable('');
	this.description = ko.observable('');
}

CEditTenantView.prototype.ViewTemplate = '%ModuleName%_EditTenantView';

CEditTenantView.prototype.getCurrentValues = function ()
{
	return [
		this.id(),
		this.name(),
		this.description()
	];
};

CEditTenantView.prototype.clearFields = function ()
{
	this.id(0);
	this.name('');
	this.description('');
};

CEditTenantView.prototype.parse = function (iEntityId, oResult)
{
	if (oResult)
	{
		this.id(iEntityId);
		this.name(oResult.Name);
		this.description(oResult.Description);
	}
	else
	{
		this.clearFields();
	}
};

CEditTenantView.prototype.getParametersForSave = function ()
{
	return {
		Id: this.id(),
		Name: this.name(),
		Description: this.description()
	};
};

module.exports = new CEditTenantView();
