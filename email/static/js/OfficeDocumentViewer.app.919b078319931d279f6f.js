webpackJsonp([17],{

/***/ 322:
/*!*************************************************!*\
  !*** ./modules/CoreWebclient/js/utils/Files.js ***!
  \*************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 184),
		
		Popups = __webpack_require__(/*! modules/CoreWebclient/js/Popups.js */ 191),
		AlertPopup = __webpack_require__(/*! modules/CoreWebclient/js/popups/AlertPopup.js */ 192),
		
		UserSettings = __webpack_require__(/*! modules/CoreWebclient/js/Settings.js */ 43),
		
		FilesUtils = {}
	;

	/**
	 * Gets link for download by hash.
	 *
	 * @param {string} sModuleName Name of module that owns the file.
	 * @param {string} sHash Hash of the file.
	 * @param {string} sPublicHash Hash of shared folder if the file is displayed by public link.
	 * 
	 * @return {string}
	 */
	FilesUtils.getDownloadLink = function (sModuleName, sHash, sPublicHash)
	{
		return sHash.length > 0 ? '?/Download/' + sModuleName + '/DownloadFile/' + sHash + '/' + (sPublicHash ? '0/' + sPublicHash : '') : '';
	};

	/**
	 * Gets link for view by hash in iframe.
	 *
	 * @param {number} iAccountId
	 * @param {string} sUrl
	 *
	 * @return {string}
	 */
	FilesUtils.getIframeWrappwer = function (iAccountId, sUrl)
	{
		return '?/Raw/Iframe/' + iAccountId + '/' + window.encodeURIComponent(sUrl) + '/';
	};

	FilesUtils.thumbQueue = (function () {

		var
			oImages = {},
			oImagesIncrements = {},
			iNumberOfImages = 2
		;

		return function (sSessionUid, sImageSrc, fImageSrcObserver)
		{
			if(sImageSrc && fImageSrcObserver)
			{
				if(!(sSessionUid in oImagesIncrements) || oImagesIncrements[sSessionUid] > 0) //load first images
				{
					if(!(sSessionUid in oImagesIncrements)) //on first image
					{
						oImagesIncrements[sSessionUid] = iNumberOfImages;
						oImages[sSessionUid] = [];
					}
					oImagesIncrements[sSessionUid]--;

					fImageSrcObserver(sImageSrc); //load image
				}
				else //create queue
				{
					oImages[sSessionUid].push({
						imageSrc: sImageSrc,
						imageSrcObserver: fImageSrcObserver,
						messageUid: sSessionUid
					});
				}
			}
			else //load images from queue (fires load event)
			{
				if(oImages[sSessionUid] && oImages[sSessionUid].length)
				{
					oImages[sSessionUid][0].imageSrcObserver(oImages[sSessionUid][0].imageSrc);
					oImages[sSessionUid].shift();
				}
			}
		};
	}());

	/**
	 * @param {string} sFileName
	 * @param {number} iSize
	 * @returns {Boolean}
	 */
	FilesUtils.showErrorIfAttachmentSizeLimit = function (sFileName, iSize)
	{
		var
			sWarning = TextUtils.i18n('COREWEBCLIENT/ERROR_UPLOAD_SIZE_DETAILED', {
				'FILENAME': sFileName,
				'MAXSIZE': TextUtils.getFriendlySize(UserSettings.AttachmentSizeLimit)
			})
		;
		
		if (UserSettings.AttachmentSizeLimit > 0 && iSize > UserSettings.AttachmentSizeLimit)
		{
			Popups.showPopup(AlertPopup, [sWarning]);
			return true;
		}
		
		return false;
	};

	module.exports = FilesUtils;


/***/ }),

/***/ 327:
/*!***************************************************************!*\
  !*** ./modules/CoreWebclient/js/models/CAbstractFileModel.js ***!
  \***************************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	var
		_ = __webpack_require__(/*! underscore */ 2),
		$ = __webpack_require__(/*! jquery */ 1),
		ko = __webpack_require__(/*! knockout */ 44),
		
		App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 182),
		FilesUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Files.js */ 322),
		TextUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Text.js */ 184),
		Types = __webpack_require__(/*! modules/CoreWebclient/js/utils/Types.js */ 181),
		UrlUtils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Url.js */ 179),
		Utils = __webpack_require__(/*! modules/CoreWebclient/js/utils/Common.js */ 217),
		
		WindowOpener = __webpack_require__(/*! modules/CoreWebclient/js/WindowOpener.js */ 199),
		
		aViewMimeTypes = [
			'image/jpeg', 'image/png', 'image/gif',
			'text/html', 'text/plain', 'text/css',
			'text/rfc822-headers', 'message/delivery-status',
			'application/x-httpd-php', 'application/javascript'
		],
		
		aViewExtensions = []
	;

	if ($('html').hasClass('pdf'))
	{
		aViewMimeTypes.push('application/pdf');
		aViewMimeTypes.push('application/x-pdf');
	}

	/**
	 * @constructor
	 */
	function CAbstractFileModel()
	{
		this.id = ko.observable('');
		this.index = ko.observable(0);
		this.fileName = ko.observable('');
		this.tempName = ko.observable('');
		this.displayName = ko.observable('');
		this.extension = ko.observable('');
		
		this.fileName.subscribe(function (sFileName) {
			this.id(sFileName);
			this.displayName(sFileName);
			this.extension(Utils.getFileExtension(sFileName));
		}, this);
		
		this.size = ko.observable(0);
		this.friendlySize = ko.computed(function () {
			return this.size() > 0 ? TextUtils.getFriendlySize(this.size()) : '';
		}, this);
		
		this.hash = ko.observable('');
		
		this.thumbUrlInQueue = ko.observable('');
		this.thumbUrlInQueueSubscribtion = this.thumbUrlInQueue.subscribe(function () {
			this.getInThumbQueue();
		}, this);

		this.thumbnailSrc = ko.observable('');
		this.thumbnailLoaded = ko.observable(false);
		this.thumbnailSessionUid = ko.observable('');

		this.mimeType = ko.observable('');
		this.uploadUid = ko.observable('');
		this.uploaded = ko.observable(false);
		this.uploadError = ko.observable(false);
		this.downloading = ko.observable(false);
		this.isViewMimeType = ko.computed(function () {
			return (-1 !== $.inArray(this.mimeType(), aViewMimeTypes));
		}, this);
		this.bHasHtmlEmbed = false;
		
		this.otherTemplates = ko.observableArray([]);

		// Some modules can override this field if it is necessary to manage it.
		this.visibleCancelButton = ko.observable(true);
		
		this.statusText = ko.observable('');
		this.statusTooltip = ko.computed(function () {
			return this.uploadError() ? this.statusText() : '';
		}, this);
		this.progressPercent = ko.observable(0);
		this.visibleProgress = ko.observable(false);
		
		this.uploadStarted = ko.observable(false);
		this.uploadStarted.subscribe(function () {
			if (this.uploadStarted())
			{
				this.uploaded(false);
				this.visibleProgress(true);
				this.progressPercent(20);
			}
			else
			{
				this.progressPercent(100);
				this.visibleProgress(false);
				this.uploaded(true);
			}
		}, this);
		
		this.downloading.subscribe(function () {
			if (this.downloading())
			{
				this.visibleProgress(true);
			}
			else
			{
				this.visibleProgress(false);
				this.progressPercent(0);
			}
		}, this);
		
		this.allowDrag = ko.observable(false);
		this.allowUpload = ko.observable(false);
		this.allowSharing = ko.observable(false);
		this.bIsSecure = ko.observable(false);
		
		this.sHeaderText = '';

		this.oActionsData = {
			'view': {
				'Text': TextUtils.i18n('COREWEBCLIENT/ACTION_VIEW_FILE'),
				'Handler': _.bind(function () { this.viewFile(); }, this)
			},
			'download': {
				'Text': TextUtils.i18n('COREWEBCLIENT/ACTION_DOWNLOAD_FILE'),
				'Handler': _.bind(function () { this.downloadFile(); }, this),
				'Tooltip': ko.computed(function () {
					var sTitle = TextUtils.i18n('COREWEBCLIENT/INFO_CLICK_TO_DOWNLOAD_FILE', {
						'FILENAME': this.fileName(),
						'SIZE': this.friendlySize()
					});

					if (this.friendlySize() === '')
					{
						sTitle = sTitle.replace(' ()', '');
					}

					return sTitle;
				}, this)
			}
		};
		
		this.allowActions = ko.observable(true);
		
		this.iconAction = ko.observable('download');
		
		this.cssClasses = ko.computed(function () {
			return this.getCommonClasses().join(' ');
		}, this);
		
		this.actions = ko.observableArray([]);
		
		this.firstAction = ko.computed(function () {
			if (this.actions().length > 1)
			{
				return this.actions()[0];
			}
			return '';
		}, this);
		
		this.secondAction = ko.computed(function () {
			if (this.actions().length === 1)
			{
				return this.actions()[0];
			}
			if (this.actions().length > 1)
			{
				return this.actions()[1];
			}
			return '';
		}, this);
		
		this.subFiles = ko.observableArray([]);
		this.subFilesExpanded = ko.observable(false);
	}

	CAbstractFileModel.prototype.addAction = function (sAction, bMain, oActionData)
	{
		if (bMain)
		{
			this.actions.unshift(sAction);
		}
		else
		{
			this.actions.push(sAction);
		}
		this.actions(_.compact(this.actions()));
		if (oActionData)
		{
			this.oActionsData[sAction] = oActionData;
		}
	};

	CAbstractFileModel.prototype.removeAction = function (sAction)
	{
		this.actions(_.without(this.actions(), sAction));
	};

	CAbstractFileModel.prototype.getMainAction = function ()
	{
		return this.actions()[0];
	};

	CAbstractFileModel.prototype.hasAction = function (sAction)
	{
		return _.indexOf(this.actions(), sAction) !== -1;
	};

	/**
	 * Returns button text for specified action.
	 * @param {string} sAction
	 * @returns string
	 */
	CAbstractFileModel.prototype.getActionText = function (sAction)
	{
		if (this.hasAction(sAction) && this.oActionsData[sAction] && (typeof this.oActionsData[sAction].Text === 'string' || _.isFunction(this.oActionsData[sAction].Text)))
		{
			return _.isFunction(this.oActionsData[sAction].Text) ? this.oActionsData[sAction].Text() : this.oActionsData[sAction].Text;
		}
		return '';
	};

	CAbstractFileModel.prototype.getActionUrl = function (sAction)
	{
		return (this.hasAction(sAction) && this.oActionsData[sAction]) ? (this.oActionsData[sAction].Url || '') : '';
	};

	/**
	 * Executes specified action.
	 * @param {string} sAction
	 */
	CAbstractFileModel.prototype.executeAction = function (sAction)
	{
		if (this.hasAction(sAction) && this.oActionsData[sAction] && _.isFunction(this.oActionsData[sAction].Handler))
		{
			this.oActionsData[sAction].Handler();
		}
	};

	/**
	 * Returns tooltip for specified action.
	 * @param {string} sAction
	 * @returns string
	 */
	CAbstractFileModel.prototype.getTooltip = function (sAction)
	{
		var mTootip = this.hasAction(sAction) && this.oActionsData[sAction] ? this.oActionsData[sAction].Tooltip : '';
		if (typeof mTootip === 'string')
		{
			return mTootip;
		}
		if (_.isFunction(mTootip))
		{
			return mTootip();
		}
		return '';
	};

	/**
	 * Returns list of css classes for file.
	 * @returns array
	 */
	CAbstractFileModel.prototype.getCommonClasses = function ()
	{
		var aClasses = [];

		if ((this.allowUpload() && !this.uploaded()) || this.downloading())
		{
			aClasses.push('incomplete');
		}
		if (this.uploadError())
		{
			aClasses.push('fail');
		}
		else
		{
			aClasses.push('success');
		}

		return aClasses;
	};

	/**
	 * Parses attachment data from server.
	 * @param {AjaxAttachmenResponse} oData
	 */
	CAbstractFileModel.prototype.parse = function (oData)
	{
		this.fileName(Types.pString(oData.FileName));
		this.tempName(Types.pString(oData.TempName));
		if (this.tempName() === '')
		{
			this.tempName(this.fileName());
		}

		this.mimeType(Types.pString(oData.MimeType));
		this.size(oData.EstimatedSize ? Types.pInt(oData.EstimatedSize) : Types.pInt(oData.SizeInBytes));

		this.hash(Types.pString(oData.Hash));

		this.parseActions(oData);

		this.uploadUid(this.hash());
		this.uploaded(true);

		if ($.isFunction(this.additionalParse))
		{
			this.additionalParse(oData);
		}
	};

	CAbstractFileModel.prototype.parseActions = function (oData)
	{
		this.thumbUrlInQueue(Types.pString(oData.ThumbnailUrl) !== '' ? Types.pString(oData.ThumbnailUrl) + '/' + Math.random() : '');
		this.commonParseActions(oData);
		this.commonExcludeActions();
	};

	CAbstractFileModel.prototype.commonExcludeActions = function ()
	{
		if (!this.isViewSupported())
		{
			this.actions(_.without(this.actions(), 'view'));
		}
	};

	CAbstractFileModel.prototype.commonParseActions = function (oData)
	{
		_.each (oData.Actions, function (oData, sAction) {
			if (!this.oActionsData[sAction])
			{
				this.oActionsData[sAction] = {};
			}
			this.oActionsData[sAction].Url = Types.pString(oData.url);
			this.actions.push(sAction);
		}, this);
	};

	CAbstractFileModel.addViewExtensions = function (aAddViewExtensions)
	{
		if (_.isArray(aAddViewExtensions))
		{
			aViewExtensions = _.union(aViewExtensions, aAddViewExtensions);
		}
	};

	CAbstractFileModel.prototype.isViewSupported = function ()
	{
		return (-1 !== $.inArray(this.mimeType(), aViewMimeTypes) || -1 !== $.inArray(this.extension(), aViewExtensions));
	};

	CAbstractFileModel.prototype.getInThumbQueue = function ()
	{
		if(this.thumbUrlInQueue() !== '' && (!this.linked || this.linked && !this.linked()))
		{
			this.thumbnailSessionUid(Date.now().toString());
			FilesUtils.thumbQueue(this.thumbnailSessionUid(), this.thumbUrlInQueue(), this.thumbnailSrc);
		}
	};

	/**
	 * Starts downloading attachment on click.
	 */
	CAbstractFileModel.prototype.downloadFile = function ()
	{
		//todo: UrlUtils.downloadByUrl in nessesary context in new window
		var 
			sDownloadLink = this.getActionUrl('download'),
			oParams = {
				'File': this,
				'CancelDownload': false
			}
		;
		if (sDownloadLink.length > 0 && sDownloadLink !== '#')
		{
			App.broadcastEvent('AbstractFileModel::FileDownload::before', oParams);
			if (!oParams.CancelDownload)
			{
				if (_.isFunction(oParams.CustomDownloadHandler))
				{
					oParams.CustomDownloadHandler();
				}
				else
				{
					UrlUtils.downloadByUrl(sDownloadLink);
				}
			}
		}
	};

	/**
	 * Can be overridden.
	 * Starts viewing attachment on click.
	 * @param {Object} oViewModel
	 * @param {Object} oEvent
	 */
	CAbstractFileModel.prototype.viewFile = function (oViewModel, oEvent)
	{
		Utils.calmEvent(oEvent);
		this.viewCommonFile();
	};

	/**
	 * Starts viewing attachment on click.
	 * @param {string=} sUrl
	 */
	CAbstractFileModel.prototype.viewCommonFile = function (sUrl)
	{
		var 
			oWin = null,
			oParams = null
		;
		
		if (!Types.isNonEmptyString(sUrl))
		{
			sUrl = UrlUtils.getAppPath() + this.getActionUrl('view');
		}

		if (sUrl.length > 0 && sUrl !== '#')
		{
			oParams = {sUrl: sUrl, index: this.index(), bBreakView: false};
			
			App.broadcastEvent('AbstractFileModel::FileView::before', oParams);
			
			if (!oParams.bBreakView)
			{
				oWin = WindowOpener.open(sUrl, sUrl, false);

				if (oWin)
				{
					oWin.focus();
				}
			}
		}
	};

	/**
	 * @param {Object} oAttachment
	 * @param {*} oEvent
	 * @return {boolean}
	 */
	CAbstractFileModel.prototype.eventDragStart = function (oAttachment, oEvent)
	{
		var oLocalEvent = oEvent.originalEvent || oEvent;
		if (oAttachment && oLocalEvent && oLocalEvent.dataTransfer && oLocalEvent.dataTransfer.setData)
		{
			oLocalEvent.dataTransfer.setData('DownloadURL', this.generateTransferDownloadUrl());
		}

		return true;
	};

	/**
	 * @return {string}
	 */
	CAbstractFileModel.prototype.generateTransferDownloadUrl = function ()
	{
		var sLink = this.getActionUrl('download');
		if ('http' !== sLink.substr(0, 4))
		{
			sLink = UrlUtils.getAppPath() + sLink;
		}

		return this.mimeType() + ':' + this.fileName() + ':' + sLink;
	};

	/**
	 * Fills attachment data for upload.
	 *
	 * @param {string} sFileUid
	 * @param {Object} oFileData
	 * @param {bool} bOnlyUploadStatus
	 */
	CAbstractFileModel.prototype.onUploadSelect = function (sFileUid, oFileData, bOnlyUploadStatus)
	{
		if (!bOnlyUploadStatus)
		{
			this.fileName(Types.pString(oFileData['FileName']));
			this.mimeType(Types.pString(oFileData['Type']));
			this.size(Types.pInt(oFileData['Size']));
		}
		
		this.uploadUid(sFileUid);
		this.uploaded(false);
		this.statusText('');
		this.progressPercent(0);
		this.visibleProgress(false);
	};

	/**
	 * Starts progress.
	 */
	CAbstractFileModel.prototype.onUploadStart = function ()
	{
		this.visibleProgress(true);
	};

	/**
	 * Fills progress upload data.
	 *
	 * @param {number} iUploadedSize
	 * @param {number} iTotalSize
	 */
	CAbstractFileModel.prototype.onUploadProgress = function (iUploadedSize, iTotalSize)
	{
		if (iTotalSize > 0)
		{
			this.progressPercent(Math.ceil(iUploadedSize / iTotalSize * 100));
			this.visibleProgress(true);
		}
	};

	/**
	 * Fills progress download data.
	 *
	 * @param {number} iDownloadedSize
	 * @param {number} iTotalSize
	 */
	CAbstractFileModel.prototype.onDownloadProgress = function (iDownloadedSize, iTotalSize)
	{
		if (iTotalSize > 0)
		{
			this.progressPercent(Math.ceil(iDownloadedSize / iTotalSize * 100));
			this.visibleProgress(this.progressPercent() < 100);
		}
	};

	/**
	 * Fills data when upload has completed.
	 *
	 * @param {string} sFileUid
	 * @param {boolean} bResponseReceived
	 * @param {Object} oResponse
	 */
	CAbstractFileModel.prototype.onUploadComplete = function (sFileUid, bResponseReceived, oResponse)
	{
		var
			bError = !bResponseReceived || !oResponse || !!oResponse.ErrorCode || !oResponse.Result || !!oResponse.Result.Error || false,
			sError = (oResponse && oResponse.ErrorCode && oResponse.ErrorCode === Enums.Errors.CanNotUploadFileLimit) ?
				TextUtils.i18n('COREWEBCLIENT/ERROR_UPLOAD_SIZE') :
				TextUtils.i18n('COREWEBCLIENT/ERROR_UPLOAD_UNKNOWN')
		;

		this.progressPercent(0);
		this.visibleProgress(false);
		
		this.uploaded(true);
		this.uploadError(bError);
		this.statusText(bError ? sError : TextUtils.i18n('COREWEBCLIENT/REPORT_UPLOAD_COMPLETE'));

		if (!bError)
		{
			this.fillDataAfterUploadComplete(oResponse, sFileUid);
			
			setTimeout((function (self) {
				return function () {
					self.statusText('');
				};
			})(this), 3000);
		}
	};

	/**
	 * Should be overriden.
	 * 
	 * @param {Object} oResult
	 * @param {string} sFileUid
	 */
	CAbstractFileModel.prototype.fillDataAfterUploadComplete = function (oResult, sFileUid)
	{
	};

	/**
	 * @param {Object} oAttachmentModel
	 * @param {Object} oEvent
	 */
	CAbstractFileModel.prototype.onImageLoad = function (oAttachmentModel, oEvent)
	{
		if(this.thumbUrlInQueue() !== '' && !this.thumbnailLoaded())
		{
			this.thumbnailLoaded(true);
			FilesUtils.thumbQueue(this.thumbnailSessionUid());
		}
	};

	/**
	 * Signalise that file download was stoped.
	 */
	CAbstractFileModel.prototype.stopDownloading = function ()
	{
		this.downloading(false);
	};

	/**
	 * Signalise that file download was started.
	 */
	CAbstractFileModel.prototype.startDownloading = function ()
	{
		this.downloading(true);
	};

	module.exports = CAbstractFileModel;


/***/ }),

/***/ 385:
/*!****************************************************!*\
  !*** ./modules/OfficeDocumentViewer/js/manager.js ***!
  \****************************************************/
/***/ (function(module, exports, __webpack_require__) {

	'use strict';

	module.exports = function (oAppData) {
		var
			App = __webpack_require__(/*! modules/CoreWebclient/js/App.js */ 182),
					
			CAbstractFileModel = __webpack_require__(/*! modules/CoreWebclient/js/models/CAbstractFileModel.js */ 327)
		;
		
		if (App.getUserRole() === Enums.UserRole.NormalUser)
		{
			return {
				start: function () {
					var aExtensionsToView = oAppData['OfficeDocumentViewer'] ? oAppData['OfficeDocumentViewer']['ExtensionsToView'] : [];
					CAbstractFileModel.addViewExtensions(aExtensionsToView);
				}
			};
		}
		
		return null;
	};


/***/ })

});