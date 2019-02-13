'use strict';

var
	_ = require('underscore'),
	$ = require('jquery'),
	ko = require('knockout'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js'),
	
	Popups = require('%PathToCoreWebclientModule%/js/Popups.js'),
	AlertPopup = require('%PathToCoreWebclientModule%/js/popups/AlertPopup.js'),
	
	App = require('%PathToCoreWebclientModule%/js/App.js'),
	Screens = require('%PathToCoreWebclientModule%/js/Screens.js')
;

/**
 * @constructor
 */
function CAjax()
{
	this.requests = ko.observableArray([]);
	
	this.aOnAllRequestsClosedHandlers = [];
	this.requests.subscribe(function () {
		if (this.requests().length === 0)
		{
			_.each(this.aOnAllRequestsClosedHandlers, function (fHandler) {
				if ($.isFunction(fHandler))
				{
					fHandler();
				}
			});
		}
	}, this);
	
	this.aAbortRequestHandlers = {};
	
	this.bAllowRequests = true;
	this.bInternetConnectionProblem = false;
}

/**
 * @param {string} sModule
 * @param {string} sMethod
 * @returns {object}
 */
CAjax.prototype.getOpenedRequest = function (sModule, sMethod)
{
	var oFoundReqData = _.find(this.requests(), function (oReqData) {
		return oReqData.Request.Module === sModule && oReqData.Request.Method === sMethod;
	});
	
	return oFoundReqData ? oFoundReqData.Request : null;
};

/**
 * @param {string=} sModule = ''
 * @param {string=} sMethod = ''
 * @returns {boolean}
 */
CAjax.prototype.hasOpenedRequests = function (sModule, sMethod)
{
	sModule = Types.pString(sModule);
	sMethod = Types.pString(sMethod);
	
	if (sMethod === '')
	{
		return this.requests().length > 0;
	}
	else
	{
		return !!_.find(this.requests(), function (oReqData) {
			if (oReqData)
			{
				var
					bComplete = oReqData.Xhr.readyState === 4,
					bAbort = oReqData.Xhr.readyState === 0 && oReqData.Xhr.statusText === 'abort',
					bSameMethod = oReqData.Request.Module === sModule && oReqData.Request.Method === sMethod
				;
				return !bComplete && !bAbort && bSameMethod;
			}
			return false;
		});
	}
};

/**
 * @param {string} sModule
 * @param {function} fHandler
 */
CAjax.prototype.registerAbortRequestHandler = function (sModule, fHandler)
{
	this.aAbortRequestHandlers[sModule] = fHandler;
};

/**
 * @param {function} fHandler
 */
CAjax.prototype.registerOnAllRequestsClosedHandler = function (fHandler)
{
	this.aOnAllRequestsClosedHandlers.push(fHandler);
};

/**
 * @param {string} sModule
 * @param {string} sMethod
 * @param {object} oParameters
 * @param {function=} fResponseHandler
 * @param {object=} oContext
 * @param {number=} iTimeout
 * @param {object=} oMainParams
 */
CAjax.prototype.send = function (sModule, sMethod, oParameters, fResponseHandler, oContext, iTimeout, oMainParams)
{
	if (this.bAllowRequests && !this.bInternetConnectionProblem)
	{
		var oRequest = _.extendOwn({
			Module: sModule,
			Method: sMethod
		}, App.getCommonRequestParameters());
		
		if (oMainParams)
		{
			oRequest = _.extendOwn(oRequest, oMainParams);
		}
		
		oParameters = oParameters || {};
		
		var oEventParams = {
			'Module': sModule,
			'Method': sMethod,
			'Parameters': oParameters,
			'ResponseHandler': fResponseHandler,
			'Context': oContext,
			'Continue': true
		};
		App.broadcastEvent('SendAjaxRequest::before', oEventParams);
		
		if (oEventParams.Continue)
		{
			oRequest.Parameters = oParameters;

			this.abortRequests(oRequest);

			this.doSend(oRequest, fResponseHandler, oContext, iTimeout);
		}
	}
};

/*************************private*************************************/

/**
 * @param {Object} oRequest
 * @param {Function=} fResponseHandler
 * @param {Object=} oContext
 * @param {number=} iTimeout
 */
CAjax.prototype.doSend = function (oRequest, fResponseHandler, oContext, iTimeout)
{
	var
		doneFunc = _.bind(this.done, this, oRequest, fResponseHandler, oContext),
		failFunc = _.bind(this.fail, this, oRequest, fResponseHandler, oContext),
		alwaysFunc = _.bind(this.always, this, oRequest),
		oXhr = null,
		oCloneRequest = _.clone(oRequest),
		sAuthToken = $.cookie('AuthToken') || '',
		oHeader = (sAuthToken !== '') ? { 'Authorization': 'Bearer ' + sAuthToken } : {}
	;
	
	oCloneRequest.Parameters = JSON.stringify(oCloneRequest.Parameters);
	
	oXhr = $.ajax({
		url: '?/Api/',
		type: 'POST',
		async: true,
		dataType: 'json',
		headers: oHeader,
		data: oCloneRequest,
		success: doneFunc,
		error: failFunc,
		complete: alwaysFunc,
		timeout: iTimeout === undefined ? 50000 : iTimeout
	});
	
	this.requests().push({ Request: oRequest, Xhr: oXhr });
};

/**
 * @param {Object} oRequest
 */
CAjax.prototype.abortRequests = function (oRequest)
{
	var fHandler = this.aAbortRequestHandlers[oRequest.Module];
	
	if ($.isFunction(fHandler) && this.requests().length > 0)
	{
		_.each(this.requests(), _.bind(function (oReqData, iIndex) {
			if (oReqData)
			{
				var oOpenedRequest = oReqData.Request;
				if (oRequest.Module === oOpenedRequest.Module)
				{
					if (fHandler(oRequest, oOpenedRequest))
					{
						oReqData.Xhr.abort();
						this.requests()[iIndex] = undefined;
					}
				}
			}
		}, this));
		
		this.requests(_.compact(this.requests()));
	}
};

/**
 * @param {object} oExcept
 */
CAjax.prototype.abortAllRequests = function (oExcept)
{
	if (typeof oExcept !== 'object')
	{
		oExcept = {
			Module: '',
			Method: ''
		};
	}
	_.each(this.requests(), function (oReqData) {
		if (oReqData && (oReqData.Request.Module !== oExcept.Module || oReqData.Request.Method !== oExcept.Method))
		{
			oReqData.Xhr.abort();
		}
	}, this);
	
	this.requests([]);
};

/**
 * @param {object} oExcept
 */
CAjax.prototype.abortAndStopSendRequests = function (oExcept)
{
	this.bAllowRequests = false;
	this.abortAllRequests(oExcept);
};

CAjax.prototype.startSendRequests = function ()
{
	this.bAllowRequests = true;
};

/**
 * @param {Object} oRequest
 * @param {Function} fResponseHandler
 * @param {Object} oContext
 * @param {{Result:boolean}} oResponse
 * @param {string} sType
 * @param {Object} oXhr
 */
CAjax.prototype.done = function (oRequest, fResponseHandler, oContext, oResponse, sType, oXhr)
{
	if (App.getUserRole() !== Enums.UserRole.Anonymous && oResponse && Types.isNumber(oResponse.AuthenticatedUserId) && oResponse.AuthenticatedUserId !== 0 && oResponse.AuthenticatedUserId !== App.getUserId())
	{
		Popups.showPopup(AlertPopup, [TextUtils.i18n('%MODULENAME%/ERROR_AUTHENTICATED_USER_CONFLICT'), function () {
			App.logoutAndGotoLogin();
		}, '', TextUtils.i18n('%MODULENAME%/ACTION_LOGOUT')]);
	}
	
	// if oResponse.Result === 0 or oResponse.Result === '' this is not an error
	if (oResponse && (oResponse.Result === false || oResponse.Result === null || oResponse.Result === undefined))
	{
		switch (oResponse.ErrorCode)
		{
			case Enums.Errors.InvalidToken:
				this.abortAndStopSendRequests();
				App.tokenProblem();
				break;
			case Enums.Errors.AuthError:
				if (App.getUserRole() !== Enums.UserRole.Anonymous)
				{
					App.logoutAndGotoLogin(Enums.Errors.AuthError);
				}
				break;
		}
		
		oResponse.Result = false;
	}
	
	this.executeResponseHandler(fResponseHandler, oContext, oResponse, oRequest);
};

/**
 * @param {Object} oRequest
 * @param {Function} fResponseHandler
 * @param {Object} oContext
 * @param {Object} oXhr
 * @param {string} sType
 * @param {string} sErrorText
 */
CAjax.prototype.fail = function (oRequest, fResponseHandler, oContext, oXhr, sType, sErrorText)
{
	var oResponse = { Result: false, ErrorCode: 0 };
	
	switch (sType)
	{
		case 'abort':
			oResponse = { Result: false, ErrorCode: Enums.Errors.NotDisplayedError };
			break;
		default:
		case 'error':
		case 'parseerror':
			if (sErrorText === '')
			{
				oResponse = { Result: false, ErrorCode: Enums.Errors.NotDisplayedError, ResponseText:  oXhr.responseText};
			}
			else
			{
				oResponse = { Result: false, ErrorCode: Enums.Errors.DataTransferFailed, ResponseText:  oXhr.responseText };
			}
			break;
	}
	
	this.executeResponseHandler(fResponseHandler, oContext, oResponse, oRequest);
};

/**
 * @param {Function} fResponseHandler
 * @param {Object} oContext
 * @param {Object} oResponse
 * @param {Object} oRequest
 */
CAjax.prototype.executeResponseHandler = function (fResponseHandler, oContext, oResponse, oRequest)
{
	if (!oResponse)
	{
		oResponse = { Result: false, ErrorCode: 0 };
	}
	
	if ($.isFunction(fResponseHandler) && !oResponse.StopExecuteResponse)
	{
		fResponseHandler.apply(oContext, [oResponse, oRequest]);
	}
	
	App.broadcastEvent('ReceiveAjaxResponse::after', {'Request': oRequest, 'Response': oResponse});
};

/**
 * @param {object} oXhr
 * @param {string} sType
 * @param {object} oRequest
 */
CAjax.prototype.always = function (oRequest, oXhr, sType)
{
	if (sType !== 'abort')
	{
		_.each(this.requests(), function (oReqData, iIndex) {
			if (oReqData && _.isEqual(oReqData.Request, oRequest))
			{
				this.requests()[iIndex] = undefined;
			}
		}, this);

		this.requests(_.compact(this.requests()));

		this.checkConnection(oRequest.Module, oRequest.Method, sType);
	}
};

CAjax.prototype.checkConnection = (function () {

	var
		iTimer = -1,
		iLastWakeTime = new Date().getTime(),
		iCurrentTime = 0,
		bAwoke = false
	;

	setInterval(function() { //fix for sleep mode
		iCurrentTime = new Date().getTime();
		bAwoke = iCurrentTime > (iLastWakeTime + 5000 + 1000);
		iLastWakeTime = iCurrentTime;
		if (bAwoke)
		{
			Screens.hideError(true);
		}
	}, 5000);

	return function (sModule, sMethod, sStatus)
	{
		clearTimeout(iTimer);
		if (sStatus !== 'error')
		{
			Ajax.bInternetConnectionProblem = false;
			Screens.hideError(true);
		}
		else
		{
			if (sModule === 'Core' && sMethod === 'Ping')
			{
				Ajax.bInternetConnectionProblem = true;
				Screens.showError(TextUtils.i18n('%MODULENAME%/ERROR_NO_INTERNET_CONNECTION'), true, true);
				iTimer = setTimeout(function () {
					Ajax.doSend({ Module: 'Core', Method: 'Ping' });
				}, 10000);
			}
			else
			{
				Ajax.doSend({ Module: 'Core', Method: 'Ping' });
			}
		}
	};
}());

var Ajax = new CAjax();

module.exports = {
	getOpenedRequest: _.bind(Ajax.getOpenedRequest, Ajax),
	hasInternetConnectionProblem: function () { return Ajax.bInternetConnectionProblem; },
	hasOpenedRequests: _.bind(Ajax.hasOpenedRequests, Ajax),
	registerAbortRequestHandler: _.bind(Ajax.registerAbortRequestHandler, Ajax),
	registerOnAllRequestsClosedHandler: _.bind(Ajax.registerOnAllRequestsClosedHandler, Ajax),
	abortAndStopSendRequests: _.bind(Ajax.abortAndStopSendRequests, Ajax),
	startSendRequests: _.bind(Ajax.startSendRequests, Ajax),
	send: _.bind(Ajax.send, Ajax)
};
