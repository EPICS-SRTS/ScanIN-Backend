webpackJsonp([12],{

/***/ 307:
/*!**************************************************************!*\
  !*** ./modules/MailSensitivityWebclientPlugin/js/manager.js ***!
  \**************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	module.exports = function (oAppData) {
		var App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 182);
		
		if (App.getUserRole() === Enums.UserRole.NormalUser)
		{
			return {
				start: function (ModulesManager) {
					if (ModulesManager.isModuleEnabled('MailWebclient'))
					{
						App.subscribeEvent('MailWebclient::RegisterMessagePaneController', function (fRegisterMessagePaneController) {
							fRegisterMessagePaneController(__webpack_require__(/*! modules/MailSensitivityWebclientPlugin/js/views/MessageControlView.js */ 308), 'BeforeMessageHeaders');
						});
						ModulesManager.run('MailWebclient', 'registerComposeToolbarController', [__webpack_require__(/*! modules/MailSensitivityWebclientPlugin/js/views/ComposeDropdownView.js */ 309)]);
					}
				}
			};
		}
		
		return null;
	};


/***/ }),

/***/ 308:
/*!*******************************************************************************!*\
  !*** ./modules/MailSensitivityWebclientPlugin/js/views/MessageControlView.js ***!
  \*******************************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		ko = __webpack_require__(/*! knockout */ 44),
				
		TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 184)
	;

	function CMessageControlView()
	{
		this.sensitivityText = ko.observable('');
		
		this.visible = ko.observable(false);
	}

	CMessageControlView.prototype.ViewTemplate = 'MailSensitivityWebclientPlugin_MessageControlView';

	/**
	 * Receives properties of the message that is displaying in the message pane. 
	 * It is called every time the message is changing in the message pane.
	 * Receives null if there is no message in the pane.
	 * 
	 * @param {Object|null} oMessageProps Information about message in message pane.
	 * @param {number} oMessageProps.iSensitivity
	 */
	CMessageControlView.prototype.doAfterPopulatingMessage = function (oMessageProps)
	{
		if (!oMessageProps || oMessageProps.iSensitivity === Enums.Sensitivity.Nothing)
		{
			this.visible(false);
		}
		else
		{
			switch (oMessageProps.iSensitivity)
			{
				case Enums.Sensitivity.Confidential:
					this.sensitivityText(TextUtils.i18n('MAILSENSITIVITYWEBCLIENTPLUGIN/INFO_CONFIDENTIAL'));
					break;
				case Enums.Sensitivity.Personal:
					this.sensitivityText(TextUtils.i18n('MAILSENSITIVITYWEBCLIENTPLUGIN/INFO_PERSONAL'));
					break;
				case Enums.Sensitivity.Private:
					this.sensitivityText(TextUtils.i18n('MAILSENSITIVITYWEBCLIENTPLUGIN/INFO_PRIVATE'));
					break;
			}
			this.visible(true);
		}
	};

	module.exports = new CMessageControlView();


/***/ }),

/***/ 309:
/*!********************************************************************************!*\
  !*** ./modules/MailSensitivityWebclientPlugin/js/views/ComposeDropdownView.js ***!
  \********************************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var ko = __webpack_require__(/*! knockout */ 44);

	/**
	 * @constructor for object that display Sensitivity button on Compose
	 */
	function CComposeDropdownView()
	{
		this.sId = 'MailSensitivity';
		this.selectedSensitivity = ko.observable(Enums.Sensitivity.Nothing).extend({'reversible': true});
	}

	CComposeDropdownView.prototype.ViewTemplate = 'MailSensitivityWebclientPlugin_ComposeDropdownView';

	/**
	 * @param {Object} oParameters
	 */
	CComposeDropdownView.prototype.doAfterApplyingMainTabParameters = function (oParameters)
	{
		this.selectedSensitivity(oParameters.Sensitivity);
	};

	/**
	 * @param {Object} oParameters
	 */
	CComposeDropdownView.prototype.doAfterPreparingMainTabParameters = function (oParameters)
	{
		oParameters.Sensitivity = this.selectedSensitivity();
	};

	/**
	 * @param {Object} oParameters
	 */
	CComposeDropdownView.prototype.doAfterPopulatingMessage = function (oParameters)
	{
		this.selectedSensitivity(oParameters.iSensitivity);
	};

	/**
	 * @param {Object} oParameters
	 */
	CComposeDropdownView.prototype.doAfterPreparingSendMessageParameters = function (oParameters)
	{
		oParameters.Sensitivity = this.selectedSensitivity();
	};

	CComposeDropdownView.prototype.commit = function () {
		this.selectedSensitivity.commit();
	};

	CComposeDropdownView.prototype.isChanged = function () {
		return this.selectedSensitivity.changed();
	};

	module.exports = new CComposeDropdownView();


/***/ })

});