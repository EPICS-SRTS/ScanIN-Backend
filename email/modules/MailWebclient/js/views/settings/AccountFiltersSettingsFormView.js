'use strict';

var
	_ = require('underscore'),
	ko = require('knockout'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
	
	Screens = require('%PathToCoreWebclientModule%/js/Screens.js'),
	ModulesManager = require('%PathToCoreWebclientModule%/js/ModulesManager.js'),
	
	CAbstractSettingsFormView = ModulesManager.run('SettingsWebclient', 'getAbstractSettingsFormViewClass'),
	
	AccountList = require('modules/%ModuleName%/js/AccountList.js'),
	Ajax = require('modules/%ModuleName%/js/Ajax.js'),
	MailCache = require('modules/%ModuleName%/js/Cache.js'),
	Settings = require('modules/%ModuleName%/js/Settings.js'),
	
	CFilterModel = require('modules/%ModuleName%/js/models/CFilterModel.js'),
	CFiltersModel = require('modules/%ModuleName%/js/models/CFiltersModel.js')
;

/**
 * @constructor
 */
function CAccountFiltersSettingsFormView()
{
	CAbstractSettingsFormView.call(this, Settings.ServerModuleName);
	
	this.bShown = false;
	
	this.foldersOptions = ko.observableArray([]);
	
	MailCache.editedFolderList.subscribe(function () {
		if (this.bShown)
		{
			this.populate();
		}
	}, this);
	
	this.loading = ko.observable(true);
	this.collection = ko.observableArray([]);

	this.fieldOptions = [
		{'text': TextUtils.i18n('%MODULENAME%/LABEL_FROM'), 'value': 0},
		{'text': TextUtils.i18n('%MODULENAME%/LABEL_TO'), 'value': 1},
		{'text': TextUtils.i18n('%MODULENAME%/LABEL_SUBJECT'), 'value': 2}
	];

	this.conditionOptions = [
		{'text': TextUtils.i18n('%MODULENAME%/LABEL_CONTAINING'), 'value': 0},
		{'text': TextUtils.i18n('%MODULENAME%/LABEL_EQUAL_TO'), 'value': 1},
		{'text': TextUtils.i18n('%MODULENAME%/LABEL_NOT_CONTAINING'), 'value': 2}
	];

	this.actionOptions = [
		{'text': TextUtils.i18n('%MODULENAME%/LABEL_MOVE_FILTER_ACTION'), 'value': 3},
		{'text': TextUtils.i18n('%MODULENAME%/LABEL_DELETE_FILTER_ACTION'), 'value': 1}
	];
	
	this.phaseArray = [''];
	
	_.each(TextUtils.i18n('%MODULENAME%/INFO_FILTER').split(/,{0,1}\s/), function (sItem) {
		var iIndex = this.phaseArray.length - 1;
		if (sItem.substr(0,1) === '%' || this.phaseArray[iIndex].substr(-1,1) === '%')
		{
			this.phaseArray.push(sItem);
		}
		else
		{
			this.phaseArray[iIndex] += ' ' + sItem;
		}
	}, this);
	
	this.firstState = null;
}

_.extendOwn(CAccountFiltersSettingsFormView.prototype, CAbstractSettingsFormView.prototype);

CAccountFiltersSettingsFormView.prototype.ViewTemplate = '%ModuleName%_Settings_AccountFiltersSettingsFormView';

CAccountFiltersSettingsFormView.prototype.onShow = function ()
{
	this.populate();
};

CAccountFiltersSettingsFormView.prototype.onHide = function ()
{
	this.bShown = false;
};

CAccountFiltersSettingsFormView.prototype.populate = function ()
{
	var
		oFolderList = MailCache.editedFolderList(),
		aOptionList = []
	;

	if (oFolderList.iAccountId === AccountList.editedId())
	{
		aOptionList = oFolderList.getOptions(TextUtils.i18n('%MODULENAME%/LABEL_FOLDER_NOT_SELECTED'), true, true, false, true);
		this.foldersOptions(aOptionList);
		this.populateFilters();
	}
	else
	{
		this.loading(true);
		this.collection([]);
	}
};

CAccountFiltersSettingsFormView.prototype.revert = function ()
{
	_.each(this.collection(), function (oFilter) {
		oFilter.revert();
	});
};

CAccountFiltersSettingsFormView.prototype.commit = function ()
{
	_.each(this.collection(), function (oFilter) {
		oFilter.commit();
	});
};

CAccountFiltersSettingsFormView.prototype.getCurrentValues = function ()
{
	return _.map(this.collection(), function (oFilter) {
		return oFilter.toString();
	}, this);
};

CAccountFiltersSettingsFormView.prototype.getParametersForSave = function ()
{
	var
		aFilters =_.map(this.collection(), function (oItem) {
			return {
				'Enable': oItem.enable() ? '1' : '0',
				'Field': oItem.field(),
				'Filter': oItem.filter(),
				'Condition': oItem.condition(),
				'Action': oItem.action(),
				'FolderFullName': oItem.folder()
			};
		})
	;
	
	return {
		'AccountID': AccountList.editedId(),
		'Filters': aFilters
	};
};

CAccountFiltersSettingsFormView.prototype.save = function ()
{
	var bCantSave =_.some(this.collection(), function (oFilter) {
		return oFilter.filter() === '' || (Types.pString(oFilter.action()) === '3' /* Move */ && oFilter.folder() === '');
	});

	if (bCantSave)
	{
		Screens.showError(TextUtils.i18n('%MODULENAME%/ERROR_FILTER_FIELDS_EMPTY'));
	}
	else
	{
		this.isSaving(true);
		this.commit();
		this.updateSavedState();
		Ajax.send('UpdateFilters', this.getParametersForSave(), this.onAccountSieveFiltersUpdateResponse, this);
	}
};

CAccountFiltersSettingsFormView.prototype.populateFilters = function ()
{
	var oAccount = AccountList.getEdited();
	
	if (oAccount)
	{
		if (oAccount.filters() !== null)
		{
			this.loading(false);
			this.collection(oAccount.filters().collection());
			this.updateSavedState();
		}
		else
		{
			this.loading(true);
			this.collection([]);
			Ajax.send('GetFilters', { 'AccountID': oAccount.id() }, this.onGetFiltersResponse, this);
		}
	}
};

/**
 * @param {Object} oFilterToDelete
 */
CAccountFiltersSettingsFormView.prototype.deleteFilter = function (oFilterToDelete)
{
	this.collection.remove(oFilterToDelete);
};

CAccountFiltersSettingsFormView.prototype.addFilter = function ()
{
	var oSieveFilter =  new CFilterModel(AccountList.editedId());
	this.collection.push(oSieveFilter);
};

/**
 * @param {string} sPart
 * @param {string} sPrefix
 * 
 * @return {string}
 */
CAccountFiltersSettingsFormView.prototype.displayFilterPart = function (sPart, sPrefix)
{
	var sTemplate = '';
	if (sPart === '%FIELD%')
	{
		sTemplate = 'Field';
	}
	else if (sPart === '%CONDITION%')
	{
		sTemplate = 'Condition';
	}
	else if (sPart === '%STRING%')
	{
		sTemplate = 'String';
	}
	else if (sPart === '%ACTION%')
	{
		sTemplate = 'Action';
	}
	else if (sPart === '%FOLDER%')
	{
		sTemplate = 'Folder';
	}
	else if (sPart.substr(0, 9) === '%DEPENDED')
	{
		sTemplate = 'DependedText';
	}
	else
	{
		sTemplate = 'Text';
	}

	return sPrefix + sTemplate;
};

/**
 * @param {string} sText
 */
CAccountFiltersSettingsFormView.prototype.getDependedText = function (sText)
{	
	sText = Types.pString(sText);
	
	if (sText)
	{
		sText = sText.replace(/%/g, '').split('=')[1] || '';
	}
	
	return sText;
};

/**
 * @param {string} sText
 * @param {Object} oParent
 */
CAccountFiltersSettingsFormView.prototype.getDependedField = function (sText, oParent)
{	
	sText = Types.pString(sText);
	
	if (sText)
	{
		sText = sText.replace(/[=](.*)/g, '').split('-')[1] || '';
		sText = sText.toLowerCase();
	}

	return oParent[sText] ? oParent[sText]() : false;
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CAccountFiltersSettingsFormView.prototype.onGetFiltersResponse = function (oResponse, oRequest)
{
	var
		oParameters = oRequest.Parameters,
		iAccountId = Types.pInt(oParameters.AccountID),
		oAccount = AccountList.getAccount(iAccountId),
		oSieveFilters = new CFiltersModel()
	;
	
	this.loading(false);

	if (oResponse && oResponse.Result && oAccount)
	{
		oSieveFilters.parse(iAccountId, oResponse.Result);
		oAccount.filters(oSieveFilters);

		if (iAccountId === AccountList.editedId())
		{
			this.populateFilters();
		}
	}
	else
	{
		Screens.showError(TextUtils.i18n('COREWEBCLIENT/ERROR_UNKNOWN'));
	}
};

/**
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CAccountFiltersSettingsFormView.prototype.onAccountSieveFiltersUpdateResponse = function (oResponse, oRequest)
{
	this.isSaving(false);

	if (oRequest && oRequest.Method)
	{
		if (oResponse && oResponse.Result)
		{
			Screens.showReport(TextUtils.i18n('%MODULENAME%/REPORT_FILTERS_UPDATE_SUCCESS'));
		}
		else
		{
			Screens.showError(TextUtils.i18n('COREWEBCLIENT/ERROR_SAVING_SETTINGS_FAILED'));
		}
	}
	else
	{
		Screens.showError(TextUtils.i18n('COREWEBCLIENT/ERROR_UNKNOWN'));
	}
};

module.exports = new CAccountFiltersSettingsFormView();
