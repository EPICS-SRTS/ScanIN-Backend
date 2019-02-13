<?php

/*
 * Copyright 2004-2015, AfterLogic Corp.
 * Licensed under AGPLv3 license or AfterLogic license
 * if commercial version of the product was purchased.
 * See the LICENSE file for a full license statement.
 */

namespace MailSo\Imap\Enumerations;

/**
 * @category MailSo
 * @package Imap
 * @subpackage Enumerations
 */
class StoreAction
{
	const SET_FLAGS = 'FLAGS';
	const SET_FLAGS_SILENT = 'FLAGS.SILENT';
	const ADD_FLAGS = '+FLAGS';
	const ADD_FLAGS_SILENT = '+FLAGS.SILENT';
	const REMOVE_FLAGS = '-FLAGS';
	const REMOVE_FLAGS_SILENT = '-FLAGS.SILENT';
	
	const SET_GMAIL_LABELS = 'X-GM-LABELS';
	const SET_GMAIL_LABELS_SILENT = 'X-GM-LABELS.SILENT';
	const ADD_GMAIL_LABELS = '+X-GM-LABELS';
	const ADD_GMAIL_LABELS_SILENT = '+X-GM-LABELS.SILENT';
	const REMOVE_GMAIL_LABELS = '-X-GM-LABELS';
	const REMOVE_GMAIL_LABELS_SILENT = '-X-GM-LABELS.SILENT';
}
