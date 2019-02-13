<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Imap\Exceptions;

/**
 * @category MailSo
 * @package Imap
 * @subpackage Exceptions
 */
class ResponseException extends \MailSo\Imap\Exceptions\Exception
{
	/**
	 * @var array
	 */
	private $aResponses;

	/**
	 * @param array $aResponses = array
	 * @param string $sMessage = ''
	 * @param int $iCode = 0
	 * @param \Exception $oPrevious = null
	 */
	public function __construct($aResponses = array(), $sMessage = '', $iCode = 0, $oPrevious = null)
	{
		if (is_array($aResponses))
		{
			$this->aResponses = $aResponses;
			
			if (empty($sMessage))
			{
				foreach ($this->GetResponses() as $oResponse)
				{
					$sMessage .= $oResponse->ToLine();
				}
			}
		}
		
		parent::__construct($sMessage, $iCode, $oPrevious);

	}

	/**
	 * @return array
	 */
	public function GetResponses()
	{
		return $this->aResponses;
	}

	/**
	 * @return \MailSo\Imap\Response|null
	 */
	public function GetLastResponse()
	{
		return 0 < count($this->aResponses) ? $this->aResponses[count($this->aResponses) - 1] : null;
	}
}
