'use strict';


module.exports = function (oAppData) {
	require('modules/%ModuleName%/js/enums.js');
	require('%PathToCoreWebclientModule%/js/vendors/jquery.cookie.js');

	var
		Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
		
		App = require('%PathToCoreWebclientModule%/js/App.js'),
		
		Settings = require('modules/%ModuleName%/js/Settings.js'),
		
		bAnonimUser = App.getUserRole() === window.Enums.UserRole.Anonymous,
		LoginView = null
	;
	
	Settings.init(oAppData);
	
	if (!App.isPublic() && bAnonimUser)
	{
		if (App.isMobile())
		{
			return {
				/**
				 * Returns login view screen as is.
				 */
				getLoginScreenView: function () {
					return require('modules/%ModuleName%/js/views/LoginView.js');
				},
				
				getHashModuleName: function () {
					return Settings.HashModuleName;
				},

				/**
				 * Redirect to custom login url if specified.
				 */
				beforeAppRunning: function () {
					if (Types.isNonEmptyString(Settings.CustomLoginUrl))
					{
						window.location.href = Settings.CustomLoginUrl;
					}
				}
			};
		}
		else
		{
			var GetLoginView = function() {
				if (LoginView === null)
				{
					LoginView = require('modules/%ModuleName%/js/views/LoginView.js');
				}
				return LoginView;
			};

			return {
				/**
				 * Returns login view screen.
				 */
				getScreens: function () {
					var oScreens = {};
					
					if (!Types.isNonEmptyString(Settings.CustomLoginUrl))
					{
						oScreens[Settings.HashModuleName] = function () {
							return require('modules/%ModuleName%/js/views/LoginView.js');
						};
					}
					
					return oScreens;
				},

				/**
				 * Redirect to custom login url if specified.
				 */
				beforeAppRunning: function () {
					if (Types.isNonEmptyString(Settings.CustomLoginUrl))
					{
						window.location.href = Settings.CustomLoginUrl;
					}
				},

				registerExtentionComponent: function (oComponent) {
					var LoginView = GetLoginView();
					LoginView.registerExtentionComponent(oComponent);
				}
			};
		}
	}
	
	return null;
};
