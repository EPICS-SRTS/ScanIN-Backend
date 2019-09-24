webpackJsonp([10],{

/***/ 304:
/*!***********************************************!*\
  !*** ./modules/MailNotesPlugin/js/manager.js ***!
  \***********************************************/
/***/ (function(module, exports, __webpack_require__) {

	/* WEBPACK VAR INJECTION */(function($) {'use strict';

	module.exports = function (oAppData) {
		var
			_ = __webpack_require__(/*! underscore */ 2),
			ko = __webpack_require__(/*! knockout */ 44),
			
			TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 184),
					
			App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 182),

			bNormalUser = App.getUserRole() === Enums.UserRole.NormalUser,
			
			sNotesFullName = 'Notes'
		;
		
		if (bNormalUser)
		{
			return {
				start: function (oModulesManager) {
					$('html').addClass('MailNotesPlugin');
					App.subscribeEvent('MailWebclient::ConstructView::before', function (oParams) {
						if (oParams.Name === 'CMailView')
						{
							var
								koFolderList = oParams.MailCache.folderList,
								koCurrentFolder = ko.computed(function () {
									return oParams.MailCache.folderList().currentFolder();
								}),
								CMessagePaneView = __webpack_require__(/*! modules/MailNotesPlugin/js/views/CMessagePaneView.js */ 305),
								oMessagePane = new CMessagePaneView(oParams.MailCache, _.bind(oParams.View.routeMessageView, oParams.View))
							;
							koCurrentFolder.subscribe(function () {
								var
									sNameSpace = koFolderList().sNamespaceFolder,
									sDelimiter = koFolderList().sDelimiter,
									sFullName = koCurrentFolder() ? koCurrentFolder().fullName() : ''
								;
								if (sNameSpace !== '')
								{
									sNotesFullName = sNameSpace + sDelimiter + 'Notes';
								}
								if (sFullName === sNotesFullName)
								{
									oParams.View.setCustomPreviewPane('MailNotesPlugin', oMessagePane);
									oParams.View.setCustomBigButton('MailNotesPlugin', function () {
										oModulesManager.run('MailWebclient', 'setCustomRouting', [sFullName, 1, '', '', '', 'create-note']);
									}, TextUtils.i18n('MAILNOTESPLUGIN/ACTION_NEW_NOTE'));
									oParams.View.resetDisabledTools('MailNotesPlugin', ['spam', 'move', 'mark']);
								}
								else
								{
									oParams.View.removeCustomPreviewPane('MailNotesPlugin');
									oParams.View.removeCustomBigButton('MailNotesPlugin');
									oParams.View.resetDisabledTools('MailNotesPlugin', []);
								}
							});
						}
					});
					App.subscribeEvent('MailWebclient::ConstructView::after', function (oParams) {
						if (oParams.Name === 'CMessageListView' && oParams.MailCache)
						{
							var
								koCurrentFolder = ko.computed(function () {
									return oParams.MailCache.folderList().currentFolder();
								})
							;
							koCurrentFolder.subscribe(function () {
								var sFullName = koCurrentFolder() ? koCurrentFolder().fullName() : '';
								if (sFullName === sNotesFullName)
								{
									oParams.View.customMessageItemViewTemplate('MailNotesPlugin_MessageItemView');
								}
								else
								{
									oParams.View.customMessageItemViewTemplate('');
								}
							});
						}
					});
					App.subscribeEvent('MailWebclient::MessageDblClick::before', _.bind(function (oParams) {
						if (oParams.Message && oParams.Message.folder() === sNotesFullName)
						{
							oParams.Cancel = true;
						}
					}, this));
				}
			};
		}
		
		return null;
	};

	/* WEBPACK VAR INJECTION */}.call(exports, __webpack_require__(/*! jquery */ 1)))

/***/ }),

/***/ 305:
/*!**************************************************************!*\
  !*** ./modules/MailNotesPlugin/js/views/CMessagePaneView.js ***!
  \**************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		$ = __webpack_require__(/*! jquery */ 1),
		ko = __webpack_require__(/*! knockout */ 44),
		
		TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 184),
		
		Ajax = __webpack_require__(/*! modules/CoreWebclient/js/Ajax.js */ 190),
		Api = __webpack_require__(/*! modules/CoreWebclient/js/Api.js */ 185),
		ModulesManager = __webpack_require__(/*! modules/CoreWebclient/js/ModulesManager.js */ 42),
		MailCache = null
	;

	function GetPlainText(sHtml)
	{
		if (typeof(sHtml) !=='string')
		{
			return '';
		}
		
		var fReplacer = function (sMatch, sLink, sLinkName) {
			var sClearLink = sLink.replace(/(\w{3,4}:\/\/)(.*)/, '$2');
			return (sLink === sLinkName || sClearLink === sLinkName) ? sLink : sLinkName + ' (' + sLink + ')';
		};
		
		return sHtml
			.replace(/\r\n/g, ' ')
			.replace(/\n/g, ' ')
			.replace(/<style[^>]*>[^<]*<\/style>/gi, '\n')
			.replace(/<br *\/{0,1}>/gi, '\n')
			.replace(/<\/p>/gi, '\n')
			.replace(/(<\/div>)*$/, '')
			.replace(/<(\/div>)+/gi, '\n')
			.replace(/<a [^>]*href="([^"]*?)"[^>]*>(.*?)<\/a>/gi, fReplacer)
			.replace(/<[^>]*>/g, '')
			.replace(/&nbsp;/g, ' ')
			.replace(/&lt;/g, '<')
			.replace(/&gt;/g, '>')
			.replace(/&amp;/g, '&')
			.replace(/&quot;/g, '"')
		;
	};

	/**
	 * @constructor
	 * @param {object} oMailCache
	 * @param {function} fRouteMessageView
	 */
	function CMessagePaneView(oMailCache, fRouteMessageView)
	{
		MailCache = oMailCache;
		this.fRouteMessageView = fRouteMessageView;
		this.currentMessage = MailCache.currentMessage;
		this.currentMessage.subscribe(this.onCurrentMessageSubscribe, this);
		this.messageText = ko.observable('');
		this.messageText.focused = ko.observable(false);
		ko.computed(function () {
			this.messageText();
			this.messageText.focused(true);
		}, this).extend({ throttle: 5 }); ;
		this.sMessageUid = '';
		this.sMessageText = '';
		this.isLoading = ko.observable(false);
		this.isSaving = ko.observable(false);
		this.createMode = ko.observable(false);
		this.saveButtonText = ko.computed(function () {
			return this.isSaving() ? TextUtils.i18n('COREWEBCLIENT/ACTION_SAVE_IN_PROGRESS') : TextUtils.i18n('COREWEBCLIENT/ACTION_SAVE');
		}, this);
		
		this.bBinded = false;
	}

	CMessagePaneView.prototype.ViewTemplate = 'MailNotesPlugin_MessagePaneView';
	CMessagePaneView.prototype.ViewConstructorName = 'CMessagePaneView';

	CMessagePaneView.prototype.onShow = function ()
	{
		this.bShown = true;
	};

	CMessagePaneView.prototype.onHide = function ()
	{
		this.bShown = false;
	};

	/**
	 * Checks if there are changes in message pane.
	 * @returns {Boolean}
	 */
	CMessagePaneView.prototype.hasUnsavedChanges = function ()
	{
		var oMessage = this.currentMessage();
		return (!oMessage || this.sMessageUid === oMessage.uid()) && this.sMessageText !== this.messageText();
	};

	/**
	 * Discards changes in message pane.
	 */
	CMessagePaneView.prototype.discardChanges = function ()
	{
		if (!this.currentMessage())
		{
			this.sMessageUid = '';
			this.sMessageText = '';
			this.messageText('');
		}
	};

	CMessagePaneView.prototype.getSubjectFromText = function (sText)
	{
		var
			aText = sText.split(/\r\n|\n/i),
			sSubject = _.find(aText, function (sTextPart) {
				return $.trim(sTextPart) !== '';
			})
		;
		
		sSubject = $.trim(sSubject);
		if (sSubject.length > 50)
		{
			sSubject = sSubject.substring(0, 50);
		}
		
		return sSubject;
	};

	CMessagePaneView.prototype.onCurrentMessageSubscribe = function ()
	{
		var oMessage = this.currentMessage();
		
		if (oMessage)
		{
			if (oMessage.isPlain())
			{
				this.messageText(oMessage.textRaw());
			}
			else
			{
				this.messageText(GetPlainText(oMessage.textRaw()));
			}
			this.sMessageUid = oMessage.uid();
			this.sMessageText = this.messageText();
			this.isLoading(oMessage.uid() !== '' && !oMessage.completelyFilled());
			if (!oMessage.completelyFilled())
			{
				var sbscr = oMessage.completelyFilled.subscribe(function () {
					this.onCurrentMessageSubscribe();
					sbscr.dispose();
				}, this);
			}
			this.isSaving(false);
		}
		else
		{
			this.sMessageUid = '';
			this.sMessageText = '';
			this.messageText('');
		}
	};

	/**
	 * @param {Object} $MailViewDom
	 */
	CMessagePaneView.prototype.onBind = function ($MailViewDom)
	{
		if (!this.bBinded)
		{
			ModulesManager.run('SessionTimeoutWeblient', 'registerFunction', [_.bind(function () {
				this.saveNote();
			}, this)]);

			$(document).on('keydown', $.proxy(function(ev) {
				if (ev.ctrlKey && ev.keyCode === Enums.Key.s)
				{
					ev.preventDefault();
					this.saveNote();
				}
			}, this));

			this.bBinded = true;
		}
	};

	CMessagePaneView.prototype.onRoute = function (aParams, oParams)
	{
		MailCache.setCurrentMessage(oParams.Uid, oParams.Folder);
		if (oParams.Custom === 'create-note')
		{
			this.messageText('');
			this.createMode(true);
		}
		else
		{
			this.createMode(false);
		}
		this.isSaving(false);
	};

	CMessagePaneView.prototype.saveNote = function ()
	{
		if (this.createMode())
		{
			this.saveNewNote();
		}
		else
		{
			this.saveEditedNote();
		}
	};

	CMessagePaneView.prototype.saveNewNote = function ()
	{
		var
			oFolder = MailCache.getCurrentFolder(),
			oParameters = {
				'AccountId': MailCache.currentAccountId(),
				'FolderFullName': oFolder.fullName(),
				'Text': this.messageText().replace(/\n/g, '<br />').replace(/\r\n/g, '<br />'),
				'Subject': this.getSubjectFromText(this.messageText())
			}
		;
		this.isSaving(true);
		this.sMessageText = this.messageText();
		Ajax.send('MailNotesPlugin', 'SaveNote', oParameters, function (oResponse) {
			this.isSaving(false);
			if (oResponse.Result)
			{
				if (this.bShown)
				{
					var sbscr = MailCache.messagesLoading.subscribe(function () {
						if (this.bShown && !MailCache.messagesLoading() && !this.currentMessage())
						{
							this.fRouteMessageView(oParameters.FolderFullName, oResponse.Result);
							sbscr.dispose();
						}
					}, this);
				}
			}
			else
			{
				Api.showErrorByCode(oResponse, TextUtils.i18n('MAILNOTESPLUGIN/ERROR_NOTE_SAVING'));
			}
			MailCache.executeCheckMail(true);
		}, this);
	};

	CMessagePaneView.prototype.saveEditedNote = function (oMessage)
	{
		if (!oMessage)
		{
			oMessage = this.currentMessage();
		}
		if (oMessage)
		{
			var
				oParameters = {
					'AccountId': MailCache.currentAccountId(),
					'FolderFullName': oMessage.folder(),
					'MessageUid': oMessage.uid(),
					'Text': this.messageText().replace(/\n/g, '<br />').replace(/\r\n/g, '<br />'),
					'Subject': this.getSubjectFromText(this.messageText())
				},
				oFolder = MailCache.getFolderByFullName(MailCache.currentAccountId(), oMessage.folder())
			;
			oFolder.markDeletedByUids([oMessage.uid()]);
			MailCache.excludeDeletedMessages();
			this.isSaving(true);
			this.sMessageText = this.messageText();
			Ajax.send('MailNotesPlugin', 'SaveNote', oParameters, function (oResponse) {
				this.isSaving(false);
				if (oResponse.Result)
				{
					if (this.bShown)
					{
						var sbscr = MailCache.messagesLoading.subscribe(function () {
							if (this.bShown && !MailCache.messagesLoading() && !this.currentMessage())
							{
								this.fRouteMessageView(oParameters.FolderFullName, oResponse.Result);
								sbscr.dispose();
							}
						}, this);
					}
				}
				else
				{
					Api.showErrorByCode(oResponse, TextUtils.i18n('MAILNOTESPLUGIN/ERROR_NOTE_SAVING'));
				}
				MailCache.executeCheckMail(true);
			}, this);
		}
	};

	CMessagePaneView.prototype.cancel = function ()
	{
		this.sMessageText = this.messageText();
		ModulesManager.run('MailWebclient', 'setCustomRouting', ['Notes', 1, '', '', '', '']);
	};

	module.exports = CMessagePaneView;


/***/ })

});