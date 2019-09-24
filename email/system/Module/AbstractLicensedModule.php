<?php
/*
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\System\Module;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2018, Afterlogic Corp.
 *
 * @package Api
 */
abstract class AbstractLicensedModule extends AbstractModule 
{
	protected $isValid = null;

	public function __construct($sPath, $sVersion = '1.0')
	{
		parent::__construct($sPath, $sVersion);
		$this->RequireModule('Licensing');
	}	

	public function isValid()
	{
		if (!isset($this->isValid))
		{
			$oLicensingDecorator = \Aurora\Modules\Licensing\Module::Decorator();
			$this->isValid = ($oLicensingDecorator->Validate(self::GetName()) && $oLicensingDecorator->ValidatePeriod(self::GetName()));
		}
		
		return $this->isValid;
	}
}