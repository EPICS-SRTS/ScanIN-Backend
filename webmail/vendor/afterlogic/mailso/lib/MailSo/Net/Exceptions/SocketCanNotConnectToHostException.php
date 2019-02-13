<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Net\Exceptions;

/**
 * @category MailSo
 * @package Net
 * @subpackage Exceptions
 */
class SocketCanNotConnectToHostException extends \MailSo\Net\Exceptions\ConnectionException
{
	/**
	 * @var string
	 */
	private $sSocketMessage;

	/**
	 * @var int
	 */
	private $iSocketCode;

	/**
	 * @param string $sSocketMessage = ''
	 * @param int $iSocketCode = 0
	 * @param string $sMessage = ''
	 * @param int $iCode = 0
	 * @param \Exception $oPrevious = null
	 */
	public function __construct($sSocketMessage = '', $iSocketCode = 0, $sMessage = '', $iCode = 0, $oPrevious = null)
	{
		parent::__construct($sMessage, $iCode, $oPrevious);

		$this->sSocketMessage = $sSocketMessage;
		$this->iSocketCode = $iSocketCode;
	}

	/**
	 * @return string
	 */
	public function getSocketMessage()
	{
		return $this->sSocketMessage;
	}

	/**
	 * @return int
	 */
	public function getSocketCode()
	{
		return $this->iSocketCode;
	}
}
