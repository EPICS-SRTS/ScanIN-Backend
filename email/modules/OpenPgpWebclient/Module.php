<?php
/**
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\OpenPgpWebclient;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2018, Afterlogic Corp.
 *
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractWebclientModule
{
	public function init() 
	{
		\Aurora\Modules\Core\Classes\User::extend(
			self::GetName(),
			[
				'EnableModule'	=> array('bool', false),
			]
		);		
		
		$this->subscribeEvent('Files::PopulateFileItem::after', array($this, 'onAfterPopulateFileItem'));
		$this->subscribeEvent('Mail::GetAttachmentContent', array($this, 'oGetAttachmentContent'));
	}
	
	/**
	 * @ignore
	 * @todo not used
	 * @param array $aArgs
	 * @param object $oItem
	 * @return boolean
	 */
	public function onAfterPopulateFileItem($aArgs, &$oItem)
	{
		if ($oItem && '.asc' === \strtolower(\substr(\trim($oItem->Name), -4)))
		{
			$oFilesDecorator = \Aurora\System\Api::GetModuleDecorator('Files');
			if ($oFilesDecorator instanceof \Aurora\System\Module\Decorator)
			{
				$mResult = $oFilesDecorator->GetFileContent($aArgs['UserId'], $oItem->TypeStr, $oItem->Path, $oItem->Name);
				if (isset($mResult))
				{
					$oItem->Content = $mResult;
				}
			}
		}
	}	
	
	public function onGetAttachmentContent($oAttachment, &$bResult)
	{
		$bResult = ($oAttachment && '.asc' === \strtolower(\substr(\trim($oAttachment->getFileName()), -4)));
	}
	
	/***** public functions might be called with web API *****/
	/**
	 * Obtains list of module settings for authenticated user.
	 * 
	 * @return array
	 */
	public function GetSettings()
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::Anonymous);
		
		$aSettings = array();
		$oUser = \Aurora\System\Api::getAuthenticatedUser();
		if ($oUser && $oUser->Role === \Aurora\System\Enums\UserRole::NormalUser)
		{
			if (isset($oUser->{self::GetName().'::EnableModule'}))
			{
				$aSettings['EnableModule'] = $oUser->{self::GetName().'::EnableModule'};
			}
		}
		return $aSettings;
	}
	
	public function UpdateSettings($EnableModule)
	{
		\Aurora\System\Api::checkUserRoleIsAtLeast(\Aurora\System\Enums\UserRole::NormalUser);
		
		$oUser = \Aurora\System\Api::getAuthenticatedUser();
		if ($oUser)
		{
			if ($oUser->Role === \Aurora\System\Enums\UserRole::NormalUser)
			{
				$oCoreDecorator = \Aurora\Modules\Core\Module::Decorator();
				$oUser->{self::GetName().'::EnableModule'} = $EnableModule;
				return $oCoreDecorator->UpdateUserObject($oUser);
			}
			if ($oUser->Role === \Aurora\System\Enums\UserRole::SuperAdmin)
			{
				return true;
			}
		}
		
		return false;
	}
	/***** public functions might be called with web API *****/
}
