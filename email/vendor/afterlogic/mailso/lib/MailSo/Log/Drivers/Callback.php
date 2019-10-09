<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Log\Drivers;

/**
 * @category MailSo
 * @package Log
 * @subpackage Drivers
 */
class Callback extends \MailSo\Log\Driver
{
	/**
	 * @var mixed
	 */
	private $fWriteCallback;

	/**
	 * @var mixed
	 */
	private $fClearCallback;

	/**
	 * @access protected
	 *
	 * @param mixed $fWriteCallback
	 * @param mixed $fClearCallback
	 */
	protected function __construct($fWriteCallback, $fClearCallback)
	{
		parent::__construct();

		$this->fWriteCallback = \is_callable($fWriteCallback) ? $fWriteCallback : null;
		$this->fClearCallback = \is_callable($fClearCallback) ? $fClearCallback : null;
	}

	/**
	 * @param mixed $fWriteCallback
	 * @param mixed $fClearCallback = null
	 *
	 * @return \MailSo\Log\Drivers\Callback
	 */
	public static function NewInstance($fWriteCallback, $fClearCallback = null)
	{
		return new self($fWriteCallback, $fClearCallback);
	}

	/**
	 * @param string|array $mDesc
	 *
	 * @return bool
	 */
	protected function writeImplementation($mDesc)
	{
		if ($this->fWriteCallback)
		{
			\call_user_func_array($this->fWriteCallback, array($mDesc));
		}

		return true;
	}
	
	/**
	 * @return bool
	 */
	protected function clearImplementation()
	{
		if ($this->fClearCallback)
		{
			\call_user_func($this->fClearCallback);
		}

		return true;
	}
}
