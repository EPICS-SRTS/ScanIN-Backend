<?php
/**
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\Contacts\Classes\Csv;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2018, Afterlogic Corp.
 *
 * @internal
 * 
 * @package Contacts
 * @subpackage Helpers
 */
class Formatter
{
	const CRLF = "\r\n";

	/**
	 * @var array
	 */
	protected $aMap;

	/**
	 * @var string
	 */
	protected $sValue;

	/**
	 * @var string
	 */
	protected $sDelimiter;

	/**
	 * @var bool
	 */
	protected $bIsHeadersInit;

	/**
	 * @var mixed
	 */
	protected $oContainer;

	public function __construct()
	{
		$this->sDelimiter = ',';

		$this->sValue = '';
		$this->oContainer = null;
		$this->bIsHeadersInit = false;

		$this->aMap = array(
			'tokens' => array(
				'Title' => 'Title',
				'First Name' => 'FirstName',
				'Middle Name' => '',
				'Last Name' => 'LastName',
				'Nick Name' => 'NickName',
				'Display Name' => 'FullName',
				'Company' => 'BusinessCompany',
				'Department' => 'BusinessDepartment',
				'Job Title' => 'BusinessJobTitle',
				'Business Email' => 'BusinessEmail',
				'Business Street' => 'BusinessAddress',
				'Business City' => 'BusinessCity',
				'Business State' => 'BusinessState',
				'Business Postal Code' => 'BusinessZip',
				'Business Country' => 'BusinessCountry',
				'Home Street' => 'PersonalAddress',
				'Home City' => 'PersonalCity',
				'Home State' => 'PersonalState',
				'Home Postal Code' => 'PersonalZip',
				'Home Country' => 'PersonalCountry',
				'Business Fax' => 'BusinessFax',
				'Business Phone' => 'BusinessPhone',
				'Home Fax' => 'PersonalFax',
				'Home Phone' => 'PersonalPhone',
				'Mobile Phone' => 'PersonalMobile',
				'E-mail Address' => 'PersonalEmail',
				'Notes' => 'Notes',
				'Other Email' => 'OtherEmail',
				'Office Location' => 'BusinessOffice',
				'Web Page' => 'PersonalWeb'
			),

			'tokensWithSpecialTreatment' => array(
				'Birthday' => array('bdayForm', 'BirthDay', 'BirthMonth', 'BirthYear'),
			)
		);
	}

	public function clear()
	{
		$this->sValue = '';
		$this->oContainer = null;
		$this->bIsHeadersInit = false;
	}

	/**
	 * @param string $sDelimiter
	 */
	public function setDelimiter($sDelimiter)
	{
		$this->sDelimiter = $sDelimiter;
	}

	/**
	 * @return bool
	 */
	public function form()
	{
		$this->sValue = '';
		$this->formHeader();
		$this->formTokens();
		return true;
	}

	/**
	 * @return bool
	 */
	protected function formHeader()
	{
		if (!$this->bIsHeadersInit && isset($this->aMap['tokens']) && is_array($this->aMap['tokens']))
		{
			$aList = array();
			foreach ($this->aMap['tokens'] as $sToken => $sPropertyName)
			{
				$aList[] = $this->escapeValue($sToken, true);
			}

			foreach ($this->aMap['tokensWithSpecialTreatment'] as $sToken => $mProperties)
			{
				$aList[] = $this->escapeValue($sToken, true);
			}

			$this->sValue .= implode($this->sDelimiter, $aList);
			$this->sValue .= \Aurora\Modules\Contacts\Classes\Csv\Formatter::CRLF;

			$this->bIsHeadersInit = true;
		}
	}

	/**
	 * @return bool
	 */
	protected function formTokens()
	{
		if ($this->bIsHeadersInit && isset($this->aMap['tokens']) && is_array($this->aMap['tokens']))
		{
			$aList = array();
			foreach ($this->aMap['tokens'] as $sToken => $sPropertyName)
			{
				if (!empty($sPropertyName))
				{
					$aList[] = $this->escapeValue($this->oContainer->{$sPropertyName}, true);
				}
				else
				{
					$aList[] = $this->escapeValue('', true);
				}
			}

			foreach ($this->aMap['tokensWithSpecialTreatment'] as $sToken => $aParams)
			{
				$sFunctionName = $aParams[0];
				$aParams[0] = $sToken;

				$mValue = (string) @call_user_func_array(array(&$this, $sFunctionName), $aParams);
				$aList[] = $this->escapeValue($mValue, true);
			}

			$this->sValue .= implode($this->sDelimiter, $aList);
			$this->sValue .= \Aurora\Modules\Contacts\Classes\Csv\Formatter::CRLF;

			$this->bIsHeadersInit = true;
		}
	}

	/**
	 * @param mixed $oContainer
	 */
	public function setContainer($oContainer)
	{
		$this->oContainer = $oContainer;
	}

	/**
	 * @return string
	 */
	public function getValue()
	{
		return $this->sValue;
	}

	/**
	 * @param string $sToken
	 * @param string $sDayFieldName
	 * @param string $sMonthFieldName
	 * @param string $sYearFieldName
	 *
	 * @return string
	 */
	protected function bdayForm($sToken, $sDayFieldName, $sMonthFieldName, $sYearFieldName)
	{
		$iMonth = $iDay = $iYear = 0;
		if ($this->oContainer)
		{
			$iDay = $this->oContainer->{$sDayFieldName};
			$iMonth = $this->oContainer->{$sMonthFieldName};
			$iYear = $this->oContainer->{$sYearFieldName};
		}

		return checkdate($iMonth, $iDay, $iYear) ? $iDay.'/'.$iMonth.'/'.$iYear : '';
	}

	/**
	 * @param string $sValue
	 * @param bool $bAddQuotation Default value is **false**.
	 *
	 * @return string
	 */
	protected function escapeValue($sValue, $bAddQuotation = false)
	{
		$sValue = str_replace('"', '""', $sValue);
		return $bAddQuotation ?
			(empty($sValue) ? '' : '"'.$sValue.'"') : $sValue;
	}
}
