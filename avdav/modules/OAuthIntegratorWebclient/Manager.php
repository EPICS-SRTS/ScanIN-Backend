<?php
/**
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\OAuthIntegratorWebclient;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2018, Afterlogic Corp.
 */
class Manager extends \Aurora\System\Managers\AbstractManager
{
	/**
	 * @var \Aurora\System\Managers\Eav
	 */
	public $oEavManager = null;
	
	public function __construct(\Aurora\System\Module\AbstractModule $oModule = null)
	{
		parent::__construct($oModule);
		
		$this->oEavManager = \Aurora\System\Managers\Eav::getInstance();
	}
	
	/**
	 * @param int $iUserId
	 * @param string $sType
	 *
	 * @return \Aurora\Modules\OAuthIntegratorWebclient\Classes\Account
	 */
	public function getAccount($iUserId, $sType)
	{
		$mResult = false;
		try
		{
			$aEntities = $this->oEavManager->getEntities(
				Classes\Account::class,
				array(),
				0,
				0,
				array(
					'IdUser' => $iUserId,
					'Type' => $sType
				));
			if (is_array($aEntities) && count($aEntities) > 0)
			{
				$mResult = $aEntities[0];
			}
		}
		catch (\Aurora\System\Exceptions\BaseException $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}	
	
	/**
	 * @param string $sIdSocial
	 * @param string $sType
	 *
	 * @return \CSocial
	 */
	public function getAccountById($sIdSocial, $sType)
	{
		$mResult = false;
		try
		{
			$aEntities = $this->oEavManager->getEntities(
				Classes\Account::class,
				array(),
				0,
				0,
				array(
					'IdSocial' => $sIdSocial,
					'Type' => $sType
				)
			);
			if (is_array($aEntities) && count($aEntities) > 0)
			{
				$mResult = $aEntities[0];
			}
			
		}
		catch (\Aurora\System\Exceptions\BaseException $oException)
		{
			$mResult = false;
			$this->setLastException($oException);
		}
		return $mResult;
	}		
	
	/**
	 * @param int $iIdUser
	 *
	 * @return array
	 */
	public function getAccounts($iIdUser)
	{
		$aResult = false;
		try
		{
			$aResult = $this->oEavManager->getEntities(
				Classes\Account::class,
				array(),
				0,
				0,
				array(
					'IdUser' => $iIdUser
				));
		}
		catch (\Aurora\System\Exceptions\BaseException $oException)
		{
			$aResult = false;
			$this->setLastException($oException);
		}
		return $aResult;
	}	
	
	/**
	 * @param \Aurora\Modules\OAuthIntegratorWebclient\Classes\Account &$oAccount
	 *
	 * @return bool
	 */
	public function createAccount(\Aurora\Modules\OAuthIntegratorWebclient\Classes\Account &$oAccount)
	{
		$bResult = false;
		try
		{
			if ($oAccount->validate())
			{
				if (!$this->isExists($oAccount))
				{
					if (!$this->oEavManager->saveEntity($oAccount))
					{
						throw new \Aurora\System\Exceptions\ManagerException(Errs::UsersManager_UserCreateFailed);
					}
				}
				else
				{
					throw new \Aurora\System\Exceptions\ManagerException(Errs::UsersManager_UserAlreadyExists);
				}
			}

			$bResult = true;
		}
		catch (\Aurora\System\Exceptions\BaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}

	/**
	 * @param \Aurora\Modules\OAuthIntegratorWebclient\Classes\Account &$oAccount
	 *
	 * @return bool
	 */
	public function updateAccount(\Aurora\Modules\OAuthIntegratorWebclient\Classes\Account &$oAccount)
	{
		$bResult = false;
		try
		{
			if ($oAccount->validate())
			{
				if (!$this->oEavManager->saveEntity($oAccount))
				{
					throw new \Aurora\System\Exceptions\ManagerException(Errs::UsersManager_UserCreateFailed);
				}
			}

			$bResult = true;
		}
		catch (\Aurora\System\Exceptions\BaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}
	
	/**
	 * @param int $iIdUser
	 * @param string $sType
	 *
	 * @return bool
	 */
	public function deleteAccount($iIdUser, $sType)
	{
		$bResult = false;
		try
		{
			$oSocial = $this->getAccount($iIdUser, $sType);
			if ($oSocial)
			{
				if (!$this->oEavManager->deleteEntity($oSocial->EntityId))
				{
					throw new \Aurora\System\Exceptions\ManagerException(Errs::UsersManager_UserDeleteFailed);
				}
				$bResult = true;
			}
		}
		catch (\Aurora\System\Exceptions\BaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;
	}
	
	/**
	 * @param int $iIdUser
	 *
	 * @return bool
	 */
	public function deleteAccountByUserId($iIdUser)
	{
		$bResult = false;
		try
		{
			$aSocials = $this->getAccounts($iIdUser);
			foreach ($aSocials as $oSocial)
			{
				if ($oSocial)
				{
					if (!$this->oEavManager->deleteEntity($oSocial->EntityId))
					{
						throw new \Aurora\System\Exceptions\ManagerException(Errs::UsersManager_UserDeleteFailed);
					}
				}
			}
			$bResult = true;
		}
		catch (\Aurora\System\Exceptions\BaseException $oException)
		{
			$bResult = false;
			$this->setLastException($oException);
		}

		return $bResult;

	}	
	
	/**
	 * @param \Aurora\Modules\OAuthIntegratorWebclient\Classes\Account &$oAccount
	 *
	 * @return bool
	 */
	public function isExists(\Aurora\Modules\OAuthIntegratorWebclient\Classes\Account $oAccount)
	{
		$bResult = false;
		
		$oResult = $this->oEavManager->getEntity($oAccount->EntityId, Classes\Account::class);
				
		if ($oResult instanceof Classes\Account)
		{
			$bResult = true;
		}
		
		return $bResult;
	}	
}
