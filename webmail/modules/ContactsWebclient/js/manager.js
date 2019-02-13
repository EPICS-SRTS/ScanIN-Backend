'use strict';

module.exports = function (oAppData) {
	var
		_ = require('underscore'),
		
		TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
		
		App = require('%PathToCoreWebclientModule%/js/App.js'),
		
		Settings = require('modules/%ModuleName%/js/Settings.js'),
		
		SuggestionsAutocomplete = require('modules/%ModuleName%/js/SuggestionsAutocomplete.js'),
		SuggestionsMethods = {
			getSuggestionsAutocompleteCallback: function () {
				return SuggestionsAutocomplete.callback;
			},
			getSuggestionsAutocompleteComposeCallback: function () {
				return SuggestionsAutocomplete.composeCallback;
			},
			getSuggestionsAutocompletePhoneCallback: function () {
				return SuggestionsAutocomplete.phoneCallback;
			},
			getSuggestionsAutocompleteDeleteHandler: function () {
				return SuggestionsAutocomplete.deleteHandler;
			},
			requestUserByPhone: function (sNumber, fCallBack, oContext) {
				SuggestionsAutocomplete.requestUserByPhone(sNumber, fCallBack, oContext);
			}
		},
				
		fRegisterMessagePaneControllerOnStart = function () {
			App.subscribeEvent('MailWebclient::RegisterMessagePaneController', function (fRegisterMessagePaneController) {
				fRegisterMessagePaneController(require('modules/%ModuleName%/js/views/VcardAttachmentView.js'), 'BeforeMessageBody');
			});
		},

		ContactsCardsMethods = {
			applyContactsCards: function ($Addresses) {
				var ContactCard = require('modules/%ModuleName%/js/ContactCard.js');
				ContactCard.applyTo($Addresses);
			}
		}
	;

	Settings.init(oAppData);
	
	require('modules/%ModuleName%/js/enums.js');
	
	if (App.getUserRole() === Enums.UserRole.NormalUser)
	{
		if (App.isMobile())
		{
			return _.extend({
				start: fRegisterMessagePaneControllerOnStart,
				getSettings: function () {
					return Settings;
				},
				getHeaderItemView: function () {
					return require('modules/%ModuleName%/js/views/HeaderItemView.js');
				}
			}, SuggestionsMethods);
		}
		else if (App.isNewTab())
		{
			return _.extend({
				start: fRegisterMessagePaneControllerOnStart
			}, SuggestionsMethods, ContactsCardsMethods);
		}
		else
		{
			require('modules/%ModuleName%/js/MainTabExtMethods.js');
			
			return _.extend({
				start: function (ModulesManager) {
					ModulesManager.run('SettingsWebclient', 'registerSettingsTab', [
						function () { return require('modules/%ModuleName%/js/views/ContactsSettingsFormView.js'); }, 
						Settings.HashModuleName, 
						TextUtils.i18n('%MODULENAME%/LABEL_SETTINGS_TAB')
					]);
					fRegisterMessagePaneControllerOnStart();
				},
				getScreens: function () {
					var oScreens = {};
					oScreens[Settings.HashModuleName] = function () {
						var CContactsView = require('modules/%ModuleName%/js/views/CContactsView.js');
						return new CContactsView();
					};
					return oScreens;
				},
				getHeaderItem: function () {
					return {
						item: require('modules/%ModuleName%/js/views/HeaderItemView.js'),
						name: Settings.HashModuleName
					};
				},
				isTeamContactsAllowed: function () {
					return _.indexOf(Settings.Storages, 'team') !== -1;
				},
				getMobileSyncSettingsView: function () {
					return require('modules/%ModuleName%/js/views/MobileSyncSettingsView.js');
				}
			}, SuggestionsMethods, ContactsCardsMethods);
		}
	}
	
	return null;
};
