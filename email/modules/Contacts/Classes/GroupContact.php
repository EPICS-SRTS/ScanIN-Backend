<?php
/**
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\Contacts\Classes;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2018, Afterlogic Corp.
 *
 * @package Classes
 * @subpackage GroupContact
 * 
 * @property string $GroupUUID
 * @property string $ContactUUID
 */
class GroupContact extends \Aurora\System\EAV\Entity
{
	protected $aStaticMap = array(
		'GroupUUID'	=> array('string', '', true),
		'ContactUUID'	=> array('string', 0, true),
	);
}
