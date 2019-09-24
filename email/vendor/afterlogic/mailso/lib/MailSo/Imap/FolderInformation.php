<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Imap;

/**
 * @category MailSo
 * @package Imap
 */
class FolderInformation
{
	/**
	 * @var string
	 */
	public $FolderName;

	/**
	 * @var bool
	 */
	public $IsWritable;

	/**
	 * @var array
	 */
	public $Flags;

	/**
	 * @var array
	 */
	public $PermanentFlags;

	/**
	 * @var int
	 */
	public $Exists;

	/**
	 * @var int
	 */
	public $Recent;

	/**
	 * @var string
	 */
	public $Uidvalidity;

	/**
	 * @var int
	 */
	public $Unread;

	/**
	 * @var string
	 */
	public $Uidnext;

	/**
	 * @access private
	 * 
	 * @param string $sFolderName
	 * @param bool $bIsWritable
	 */
	private function __construct($sFolderName, $bIsWritable)
	{
		$this->FolderName = $sFolderName;
		$this->IsWritable = $bIsWritable;
		$this->Exists = null;
		$this->Recent = null;
		$this->Flags = array();
		$this->PermanentFlags = array();

		$this->Unread = null;
		$this->Uidnext = null;
	}

	/**
	 * @param string $sFolderName
	 * @param bool $bIsWritable
	 *
	 * @return \MailSo\Imap\FolderInformation
	 */
	public static function NewInstance($sFolderName, $bIsWritable)
	{
		return new self($sFolderName, $bIsWritable);
	}

	/**
	 * @param string $sFlag
	 *
	 * @return bool
	 */
	public function IsFlagSupported($sFlag)
	{
		return in_array('\\*', $this->PermanentFlags) || in_array($sFlag, $this->PermanentFlags);
	}
}
