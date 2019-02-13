'use strict';

var
	$ = require('jquery'),
	ko = require('knockout'),
	_ = require('underscore'),
	
	TextUtils = require('%PathToCoreWebclientModule%/js/utils/Text.js'),
	Types = require('%PathToCoreWebclientModule%/js/utils/Types.js')
;


var CustomTooltip = {
	_$Region: null,
	_$ArrowTop: null,
	_$Text: null,
	_$ArrowBottom: null,
	_iArrowBorderLeft: 0,
	_iArrowMarginLeft: 0,
	_iLeftShift: 0,
	_bInitialized: false,
	_bShown: false,
	
	iHideTimer: 0,
	iTimer: 0,
	
	init: function ()
	{
		if (!this._bInitialized)
		{
			this._$Region = $('<span class="custom_tooltip"></span>').appendTo('body').hide();
			this._$ArrowTop = $('<span class="custom_tooltip_arrow top"></span>').appendTo(this._$Region);
			this._$Text = $('<span class="custom_tooltip_text"></span>').appendTo(this._$Region);
			this._$ArrowBottom = $('<span class="custom_tooltip_arrow bottom_arrow"></span>').appendTo(this._$Region);
			
			this._iArrowMarginLeft = Types.pInt(this._$ArrowTop.css('margin-left'));
			this._iArrowBorderLeft = Types.pInt(this._$ArrowTop.css('border-left-width'));
			this._iLeftShift = Types.pInt(this._$Region.css('margin-left')) + this._iArrowMarginLeft + this._iArrowBorderLeft;
			
			this._bInitialized = true;
		}
		
		this._$ArrowTop.show();
		this._$ArrowBottom.hide();
		this._$ArrowTop.css({
			'margin-left': this._iArrowMarginLeft + 'px'
		});
		this._$ArrowBottom.css({
			'margin-left': this._iArrowMarginLeft + 'px'
		});
	},
	
	show: function (sText, $ItemToAlign)
	{
		this.init();
		
		var
			oItemOffset = $ItemToAlign.offset(),
			iItemWidth = $ItemToAlign.width(),
			iItemHalfWidth = (iItemWidth < 70) ? iItemWidth/2 : iItemWidth/4,
			iItemPaddingLeft = Types.pInt($ItemToAlign.css('padding-left')),
			jqBody = $('body')
		;
		
		this._$Text.html(sText);
		this._bShown = true;
		this._$Region.stop().fadeIn(260, _.bind(function () {
			if (!this._bShown)
			{
				this._$Region.hide();
			}
		}, this)).css({
			'top': oItemOffset.top + $ItemToAlign.outerHeight() + 1,
			'left': oItemOffset.left + iItemPaddingLeft + iItemHalfWidth - this._iLeftShift,
			'right': 'auto'
		});
		
		if (jqBody.outerHeight() < this._$Region.outerHeight() + this._$Region.offset().top)
		{
			this._$ArrowTop.hide();
			this._$ArrowBottom.show();
			this._$Region.css({
				'top': oItemOffset.top - this._$Region.outerHeight()
			});
		}

		setTimeout(function () {
			if (jqBody.width() < (this._$Region.outerWidth(true) + this._$Region.offset().left))
			{
				this._$Region.css({
					'left': 'auto',
					'right': 0
				});
				this._$ArrowTop.css({
					'margin-left': (iItemHalfWidth + oItemOffset.left - this._$Region.offset().left - this._iArrowBorderLeft) + 'px'
				});
				this._$ArrowBottom.css({
					'margin-left': (iItemHalfWidth + oItemOffset.left - this._$Region.offset().left - this._iArrowBorderLeft + Types.pInt(this._$Region.css('margin-right'))) + 'px'
				});
			}
		}.bind(this), 1);
	},
	
	hide: function ()
	{
		if (this._bInitialized)
		{
			this._bShown = false;
			this._$Region.hide();
		}
	}
};

function InitCustomTooltip(oElement, oCommand)
{
	var
		sTooltipText = _.isFunction(oCommand) ? oCommand() : TextUtils.i18n(oCommand),
		$Element = $(oElement),
		$Dropdown = $Element.find('span.dropdown'),
		bShown = false,
		fMouseIn = function () {
			var $ItemToAlign = $(this);
			if (!$ItemToAlign.hasClass('expand'))
			{
				clearTimeout(CustomTooltip.iHideTimer);
				bShown = true;
				clearTimeout(CustomTooltip.iTimer);
				CustomTooltip.iTimer = setTimeout(function () {
					if (bShown)
					{
						if ($ItemToAlign.hasClass('expand'))
						{
							bShown = false;
							clearTimeout(CustomTooltip.iTimer);
							CustomTooltip.hide();
						}
						else
						{
							CustomTooltip.show(sTooltipText, $ItemToAlign);
						}
					}
				}, 100);
			}
		},
		fMouseOut = function () {
			clearTimeout(CustomTooltip.iHideTimer);
			CustomTooltip.iHideTimer = setTimeout(function () {
				bShown = false;
				clearTimeout(CustomTooltip.iTimer);
				CustomTooltip.hide();
			}, 10);
		},
		fEmpty = function () {},
		fBindEvents = function () {
			$Element.unbind('mouseover', fMouseIn);
			$Element.unbind('mouseout', fMouseOut);
			$Element.unbind('click', fMouseOut);
			$Dropdown.unbind('mouseover', fMouseOut);
			$Dropdown.unbind('mouseout', fEmpty);
			if (sTooltipText !== '')
			{
				$Element.bind('mouseover', fMouseIn);
				$Element.bind('mouseout', fMouseOut);
				$Element.bind('click', fMouseOut);
				$Dropdown.bind('mouseover', fMouseOut);
				$Dropdown.bind('mouseout', fEmpty);
			}
		},
		fSubscribtion = null
	;
	
	fBindEvents();

	if (_.isFunction(oCommand) && _.isFunction(oCommand.subscribe) && fSubscribtion === null)
	{
		fSubscribtion = oCommand.subscribe(function (sValue) {
			sTooltipText = sValue;
			fBindEvents();
		});
	}
}

ko.bindingHandlers.customTooltip = {
	'update': function (oElement, fValueAccessor) {
		InitCustomTooltip(oElement, fValueAccessor());
	}
};

module.exports = {
	init: InitCustomTooltip
};
