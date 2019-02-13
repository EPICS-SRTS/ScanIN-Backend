<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Log\Enumerations;

/**
 * @category MailSo
 * @package Log
 * @subpackage Enumerations
 */
class Type
{
	const INFO = 0;
	const NOTICE = 1;
	const WARNING = 2;
	const ERROR = 3;
	const SECURE = 4;
	const NOTE = 5;
	const TIME = 6;
	const MEMORY = 7;
	const TIME_DELTA = 8;

	const NOTICE_PHP = 11;
	const WARNING_PHP = 12;
	const ERROR_PHP = 13;
}
