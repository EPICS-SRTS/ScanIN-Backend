'use strict';

var
	_ = require('underscore'),
	$ = require('jquery'),
	ko = require('knockout'),
	moment = require('moment'),
	
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
	
	UserSettings = require('%PathToCoreWebclientModule%/js/Settings.js'),
	
	Utils = {}
;

require('jquery.easing');


/**
 * @param {(Object|null|undefined)} oContext
 * @param {Function} fExecute
 * @param {(Function|boolean|null)=} mCanExecute
 * @return {Function}
 */
Utils.createCommand = function (oContext, fExecute, mCanExecute)
{
	var
		fResult = fExecute ? function () {
			if (fResult.canExecute && fResult.canExecute())
			{
				return fExecute.apply(oContext, Array.prototype.slice.call(arguments));
			}
			return false;
		} : function () {}
	;

	fResult.enabled = ko.observable(true);

	if ($.isFunction(mCanExecute))
	{
		fResult.canExecute = ko.computed(function () {
			return fResult.enabled() && mCanExecute.call(oContext);
		});
	}
	else
	{
		if (mCanExecute === undefined)
		{
			mCanExecute = true;
		}
		fResult.canExecute = ko.computed(function () {
			return fResult.enabled() && !!mCanExecute;
		});
	}
	
	return fResult;
};

Utils.isTextFieldFocused = function ()
{
	var
		mTag = document && document.activeElement ? document.activeElement : null,
		mTagName = mTag ? mTag.tagName : null,
		mTagType = mTag && mTag.type ? mTag.type.toLowerCase() : null,
		mContentEditable = mTag ? mTag.contentEditable : null
	;
	return ('INPUT' === mTagName && (mTagType === 'text' || mTagType === 'password' || mTagType === 'email' || mTagType === 'search')) ||
		'TEXTAREA' === mTagName || 'IFRAME' === mTagName || mContentEditable === 'true';
};

/**
 * @param {object} oEvent
 */
Utils.calmEvent  = function (oEvent)
{
	if (oEvent)
	{
		if (oEvent.stop)
		{
			oEvent.stop();
		}
		if (oEvent.preventDefault)
		{
			oEvent.preventDefault();
		}
		if (oEvent.stopPropagation)
		{
			oEvent.stopPropagation();
		}
		if (oEvent.stopImmediatePropagation)
		{
			oEvent.stopImmediatePropagation();
		}
		oEvent.cancelBubble = true;
		oEvent.returnValue = false;
	}
};

Utils.removeSelection = function ()
{
	if (window.getSelection)
	{
		window.getSelection().removeAllRanges();
	}
	else if (document.selection)
	{
		document.selection.empty();
	}
};

Utils.desktopNotify = (function ()
{
	var aNotifications = [];

	return function (oData) {
		var AppTab = require('%PathToCoreWebclientModule%/js/AppTab.js');
		
		if (oData && UserSettings.AllowDesktopNotifications && window.Notification && !AppTab.focused())
		{
			switch (oData.action)
			{
				case 'show':
					if (window.Notification.permission !== 'denied')
					{
						// oData - action, body, dir, lang, tag, icon, callback, timeout
						var
							oOptions = { //https://developer.mozilla.org/en-US/docs/Web/API/Notification
								body: oData.body || '', //A string representing an extra content to display within the notification
								dir: oData.dir || 'auto', //The direction of the notification; it can be auto, ltr, or rtl
								lang: oData.lang || '', //Specify the lang used within the notification. This string must be a valid BCP 47 language tag
								tag: oData.tag || Math.floor(Math.random() * (1000 - 100) + 100), //An ID for a given notification that allows to retrieve, replace or remove it if necessary
								icon: oData.icon || false //The URL of an image to be used as an icon by the notification
							},
							oNotification,
							fShowNotification = function() {
								oNotification = new window.Notification(oData.title, oOptions); //Firefox and Safari close the notifications automatically after a few moments, e.g. 4 seconds.
								oNotification.onclick = function (oEv) { //there are also onshow, onclose & onerror events
									if(oData.callback)
									{
										oData.callback();
									}
									oNotification.close();
								};

								if (oData.timeout)
								{
									setTimeout(function() { oNotification.close(); }, oData.timeout);
								}
								aNotifications.push(oNotification);
							}
						;
						
						if (window.Notification.permission === 'granted')
						{
							fShowNotification();
						}
						else if (window.Notification.permission === 'default')
						{
							window.Notification.requestPermission(function (sPermission) {
								if(sPermission === 'granted')
								{
									fShowNotification();
								}
							});
						}
					}
					break;
				case 'hide':
					_.each(aNotifications, function (oNotifi, ikey) {
						if (oData.tag === oNotifi.tag)
						{
							oNotifi.close();
							aNotifications.splice(ikey, 1);
						}
					});
					break;
				case 'hideAll':
					_.each(aNotifications,function (oNotifi) {
						oNotifi.close();
					});
					aNotifications.length = 0;
					break;
			}
		}
	};
}());

/**
 * @param {string} sFile
 * 
 * @return {string}
 */
Utils.getFileExtension = function (sFile)
{
	var 
		sResult = '',
		iIndex = sFile.lastIndexOf('.')
	;
	
	if (iIndex > -1)
	{
		sResult = sFile.substr(iIndex + 1);
	}

	return sResult;
};

Utils.draggableItems = function ()
{
	return $('<div class="draggable"><div class="content"><span class="count-text"></span></div></div>').appendTo('#pSevenHidden');
};

Utils.uiDropHelperAnim = function (oEvent, oUi)
{
	var
		iLeft = 0,
		iTop = 0,
		iNewLeft = 0,
		iNewTop = 0,
		iWidth = 0,
		iHeight = 0,
		helper = oUi.helper.clone().appendTo('#pSevenHidden'),
		target = $(oEvent.target).find('.animGoal'),
		position = null
	;

	target = target[0] ? $(target[0]) : $(oEvent.target);
	position = target && target[0] ? target.offset() : null;

	if (position)
	{
		iLeft = window.Math.round(position.left);
		iTop = window.Math.round(position.top);

		iWidth = target.width();
		iHeight = target.height();

		iNewLeft = iLeft;
		if (0 < iWidth)
		{
			iNewLeft += window.Math.round(iWidth / 2);
		}

		iNewTop = iTop;
		if (0 < iHeight)
		{
			iNewTop += window.Math.round(iHeight / 2);
		}

		helper.animate({
			'left': iNewLeft + 'px',
			'top': iNewTop + 'px',
			'font-size': '0px',
			'opacity': 0
		}, 800, 'easeOutQuint', function() {
			$(this).remove();
		});
	}
};

/**
 * @param {string} sName
 * @return {boolean}
 */
Utils.validateFileOrFolderName = function (sName)
{
	sName = $.trim(sName);
	return '' !== sName && !/["\/\\*?<>|:]/.test(sName);
};

/**
 * @param {string} sFile
 * 
 * @return {string}
 */
Utils.getFileNameWithoutExtension = function (sFile)
{
	var 
		sResult = sFile,
		iIndex = sFile.lastIndexOf('.')
	;
	if (iIndex > -1)
	{
		sResult = sFile.substr(0, iIndex);	
	}
	return sResult;
};

/**
 * @param {Object} oElement
 * @param {Object} oItem
 */
Utils.defaultOptionsAfterRender = function (oElement, oItem)
{
	if (oItem && oItem.disable !== undefined)
	{
		ko.applyBindingsToNode(oElement, {
			'disable': !!oItem.disable
		}, oItem);
	}
};

/**
 * @param {string} sDateFormat
 * 
 * @return string
 */
Utils.getDateFormatForMoment = function (sDateFormat)
{
	var sMomentDateFormat = 'MM/DD/YYYY';
	
	switch (sDateFormat)
	{
		case 'MM/DD/YYYY':
			sMomentDateFormat = 'MM/DD/YYYY';
			break;
		case 'DD/MM/YYYY':
			sMomentDateFormat = 'DD/MM/YYYY';
			break;
		case 'DD Month YYYY':
			sMomentDateFormat = 'DD MMMM YYYY';
			break;
	}
	
	return sMomentDateFormat;
};

Utils.log = (function () {

	var
		$log = null,
		aLog = []
	;

	return function () {
		var
			TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
			Browser = require('%PathToCoreWebclientModule%/js/Browser.js'),
			aNewRow = []
		;
		
		if (!UserSettings.AllowClientDebug || Browser.ie9AndBelow)
		{
			return;
		}

		function fCensor(mKey, mValue) {
			if (typeof(mValue) === 'string' && mValue.length > 50)
			{
				return mValue.substring(0, 50);
			}

			return mValue;
		}

		if (!$log)
		{
			$log = $('<div style="display: none;"></div>').appendTo('body');
		}
		
		_.each(arguments, function (mArg) {
			var sRowPart = typeof(mArg) === 'string' ? mArg : JSON.stringify(mArg, fCensor);
			if (aNewRow.length === 0)
			{
				sRowPart = ' *** ' + sRowPart + ' *** ';
			}
			aNewRow.push(sRowPart);
		});

		aNewRow.push(moment().format(' *** D MMMM, YYYY, HH:mm:ss *** '));

		if (aLog.length > 200)
		{
			aLog.shift();
		}

		aLog.push(TextUtils.encodeHtml(aNewRow.join(', ')));

		$log.html(aLog.join('<br /><br />'));
	};
}());

/**
 * @param {string} sUniqVal
 */
Utils.getHash = function (sUniqVal)
{
	var
		iHash = 0,
		iIndex = 0,
		iLen = sUniqVal.length
	;
	
	while (iIndex < iLen)
	{
		iHash  = ((iHash << 5) - iHash + sUniqVal.charCodeAt(iIndex++)) << 0;
	}
	
	return Types.pString(iHash);
};

module.exports = Utils;
