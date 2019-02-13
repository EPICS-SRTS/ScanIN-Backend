'use strict';

var
	_ = require('underscore'),
	$ = require('jquery'),
	ko = require('knockout'),
	
	CustomTooltip = require('%PathToCoreWebclientModule%/js/CustomTooltip.js'),
	ModulesManager = require('%PathToCoreWebclientModule%/js/ModulesManager.js'),
	Screens = require('%PathToCoreWebclientModule%/js/Screens.js'),
	
	ComposeMessageToAddressesFunc = ModulesManager.run('MailWebclient', 'getComposeMessageToAddresses'),
	SearchMessagesInCurrentFolderFunc = ModulesManager.run('MailWebclient', 'getSearchMessagesInCurrentFolder'),
	
	Popups = require('%PathToCoreWebclientModule%/js/Popups.js'),
	CreateContactPopup = require('modules/%ModuleName%/js/popups/CreateContactPopup.js'),
	
	ContactsCache = require('modules/%ModuleName%/js/Cache.js'),
	
	oContactCardsView = {
		contacts: ko.observableArray([]),
		ViewTemplate: '%ModuleName%_ContactCardsView',
		bAllowComposeMessageToAddresses: _.isFunction(ComposeMessageToAddressesFunc),
		searchMessagesInCurrentFolder: SearchMessagesInCurrentFolderFunc || function () {},
		bAllowSearchMessagesInCurrentFolder: _.isFunction(SearchMessagesInCurrentFolderFunc),
		add: function (aContacts) {
			var aDiffContacts = _.filter(this.contacts(), function (oContact) {
				return -1 === $.inArray(oContact.email(), _.keys(aContacts));
			});
			this.contacts(aDiffContacts.concat(_.compact(_.values(aContacts))));
		}
	}
;

Screens.showAnyView(oContactCardsView);

/**
 * @param {Object} $Element
 * @param {String} sAddress
 */
function BindContactCard($Element, sAddress)
{
	var
		$Popup = $('div.item_viewer[data-email=\'' + sAddress + '\']'),
		bPopupOpened = false,
		iCloseTimeoutId = 0,
		fOpenPopup = function () {
			if ($Popup && $Element)
			{
				bPopupOpened = true;
				clearTimeout(iCloseTimeoutId);
				setTimeout(function () {
					var
						oOffset = $Element.offset(),
						iLeft, iTop, iFitToScreenOffset
					;
					if (bPopupOpened && oOffset.left + oOffset.top !== 0)
					{
						iLeft = oOffset.left + 10;
						iTop = oOffset.top + $Element.height() + 6;
						iFitToScreenOffset = $(window).width() - (iLeft + 396); //396 - popup outer width

						if (iFitToScreenOffset > 0)
						{
							iFitToScreenOffset = 0;
						}
						$Popup.addClass('expand').offset({'top': iTop, 'left': iLeft + iFitToScreenOffset});
					}
				}, 180);
			}
		},
		fClosePopup = function () {
			if (bPopupOpened && $Popup && $Element)
			{
				bPopupOpened = false;
				iCloseTimeoutId = setTimeout(function () {
					if (!bPopupOpened)
					{
						$Popup.removeClass('expand');
					}
				}, 200);
			}
		}
	;

	if ($Popup.length > 0)
	{
		$Element
			.off()
			.on('mouseover', function () {
				$Popup
					.off()
					.on('mouseenter', fOpenPopup)
					.on('mouseleave', fClosePopup)
					.find('.link, .button')
					.off('.links')
					.on('click.links', function () {
						bPopupOpened = false;
						$Popup.removeClass('expand');
					})
				;

				setTimeout(function () {
					$Popup
						.find('.link, .button')
						.off('click.links')
						.on('click.links', function () {
							bPopupOpened = false;
							$Popup.removeClass('expand');
						});
				}.bind(this), 100);

				fOpenPopup();
			})
			.on('mouseout', fClosePopup)
		;

		bPopupOpened = false;
		$Popup.removeClass('expand');
	}
	else
	{
		$Element.off();
	}
}

function ClearElement($Element)
{
	if ($Element.next().hasClass('add_contact'))
	{
		$Element.next().remove();
	}
	$Element.removeClass('found');
	$Element.parent().removeClass('found_contact');
	$Element.off();
}

/**
 * @param {Array} aElements
 * @param {Array} aContacts
 */
function OnContactResponse(aElements, aContacts)
{
	_.each(aElements, function ($Element) {
		var
			sEmail = $Element.attr('data-email'), // $Element.data('email') returns wrong values if data-email was changed by knockoutjs
			oContact = aContacts[sEmail]
		;
		
		if (oContact !== undefined)
		{
			ClearElement($Element);
			
			if (oContact === null)
			{
				var $add = $('<span class="add_contact"></span>');
				$Element.after($add);
				CustomTooltip.init($add, '%MODULENAME%/ACTION_ADD_TO_CONTACTS');
				$add.on('click', function () {
					Popups.showPopup(CreateContactPopup, [$Element.attr('data-name'), sEmail, function (aContacts) {
						_.each(aElements, function ($El) {
							if ($El.attr('data-email') === sEmail)
							{
								ClearElement($El);
								$El.addClass('found');
								$El.parent().addClass('found_contact');
								oContactCardsView.add(aContacts);
								BindContactCard($El, sEmail);
							}
						});
					}]);
				});
			}
			else
			{
				$Element.addClass('found');
				$Element.parent().addClass('found_contact');
				oContactCardsView.add(aContacts);
				BindContactCard($Element, sEmail);
			}
		}
	});
}

module.exports = {
	applyTo: function ($Addresses) {
		var
			aElements = _.map($Addresses, function (oElement) {
				return $(oElement);
			}),
			aEmails = _.uniq(_.map(aElements, function ($Element) {
				return $Element && $Element.attr('data-email');
			}))
		;

		ContactsCache.getContactsByEmails(aEmails, _.bind(OnContactResponse, {}, aElements));
	}
};
