<?php
/**
 * This code is licensed under AGPLv3 license or AfterLogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\Contacts;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing AfterLogic Software License
 * @copyright Copyright (c) 2018, Afterlogic Corp.
 *
 * @ignore
 */
class Manager extends \Aurora\System\Managers\AbstractManager
{
	private $oEavManager = null;

	/**
	 * @param \Aurora\System\Module\AbstractModule $oModule
	 */
	public function __construct(\Aurora\System\Module\AbstractModule $oModule = null)
	{
		parent::__construct($oModule);

		if ($oModule instanceof \Aurora\System\Module\AbstractModule)
		{
			$this->oEavManager = \Aurora\System\Managers\Eav::getInstance();
		}
	}
	
	/**
	 * 
	 * @param string $sUUID
	 * @return \Aurora\Modules\Contacts\Classes\Contact
	 */
	public function getContact($sUUID)
	{
		$oContact = $this->oEavManager->getEntity($sUUID, Classes\Contact::class);
		if ($oContact)
		{
			$oContact->GroupsContacts = $this->getGroupContacts(null, $sUUID);
		}
		return $oContact;
	}
	
	/**
	 * 
	 * @param string $sEmail
	 * @return \Aurora\Modules\Contacts\Classes\Contact
	 */
	public function getContactByEmail($iUserId, $sEmail)
	{
		$oContact = null;
		$aViewAttrs = array();
		$aFilters = array(
			'$AND' => array(
				'ViewEmail' => array($sEmail, '='),
				'IdUser' => array($iUserId, '='),
			)
		);
		$aOrderBy = array('FullName');
		$aContacts = $this->oEavManager->getEntities(
			Classes\Contact::class,
			$aViewAttrs, 
			0, 
			0, 
			$aFilters, 
			$aOrderBy
		);
		if (count($aContacts) > 0)
		{
			$oContact = $aContacts[0];
			$oContact->GroupsContacts = $this->getGroupContacts(null, $oContact->UUID);
		}
		return $oContact;
	}
	
	/**
	 * Returns group item identified by its ID.
	 * 
	 * @param string $sUUID Group ID 
	 * 
	 * @return \Aurora\Modules\Contacts\Classes\Group
	 */
	public function getGroup($sUUID)
	{
		return $this->oEavManager->getEntity($sUUID, Classes\Group::class);
	}
	
	/**
	 * Returns group item identified by its name.
	 * 
	 * @param string $sName Group name
	 * 
	 * @return \Aurora\Modules\Contacts\Classes\Group
	 */
	public function getGroupByName($sName, $iUserId)
	{
		$oGroup = null;
		$aFilters = [
			'$AND' => [
				'Name' => [$sName, '='],
				'IdUser' => [$iUserId, '=']
			]
		];
		$aGroups = $this->oEavManager->getEntities(
			Classes\Group::class,
			[],
			0,
			0,
			$aFilters
		);
		if (count($aGroups) > 0)
		{
			$oGroup = $aGroups[0];
		}
		return $oGroup;
	}

	/**
	 * Updates contact information. Using this method is required to finalize changes made to the contact object. 
	 * 
	 * @param \Aurora\Modules\Contacts\Classes\Contact $oContact  Contact object to be updated 
	 * @param bool $bUpdateFromGlobal
	 * 
	 * @return bool
	 */
	public function updateContact($oContact)
	{
		$oContact->DateModified = date('Y-m-d H:i:s');
		$res = $this->oEavManager->saveEntity($oContact);
		if ($res)
		{
			$this->updateContactGroups($oContact);
		}
		
		return $res;
	}
	
	/**
	 * 
	 * @param type $oContact
	 */
	public function updateContactGroups($oContact)
	{
		$aGroupContact = $this->getGroupContacts(null, $oContact->UUID);

		$compare_func = function($oGroupContact1, $oGroupContact2) {
			if ($oGroupContact1->GroupUUID === $oGroupContact2->GroupUUID)
			{
				return 0;
			}
			if ($oGroupContact1->GroupUUID > $oGroupContact2->GroupUUID)
			{
				return -1;
			}
			return 1;
		};

		$aGroupContactToDelete = array_udiff($aGroupContact, $oContact->GroupsContacts, $compare_func);
		$aGroupContactUUIDsToDelete = array_map(
			function($oGroupContact) { 
				return $oGroupContact->UUID; 
			}, 
			$aGroupContactToDelete
		);
		$this->oEavManager->deleteEntities($aGroupContactUUIDsToDelete);

		$aGroupContactToAdd = array_udiff($oContact->GroupsContacts, $aGroupContact, $compare_func);
		foreach ($aGroupContactToAdd as $oGroupContact)
		{
			$this->oEavManager->saveEntity($oGroupContact);
		}		
	}
	
	/**
	 * Updates group information. Using this method is required to finalize changes made to the group object. 
	 * 
	 * @param \Aurora\Modules\Contacts\Classes\Group $oGroup
	 *
	 * @return bool
	 */
	public function updateGroup($oGroup)
	{
		return $this->oEavManager->saveEntity($oGroup);
	}

	/**
	 * Returns list of contacts which match the specified criteria 
	 * 
	 * @param int $iUserId User ID 
	 * @param string $sSearch Search pattern. Default value is empty string.
	 * @param string $sFirstCharacter If specified, will only return contacts with names starting from the specified character. Default value is empty string.
	 * @param string $sGroupUUID. Default value is **''**.
	 * @param int $iTenantId Group ID. Default value is null.
	 * @param bool $bAll Default value is null
	 * 
	 * @return int
	 */
	public function getContactsCount($aFilters = [], $sGroupUUID = '')
	{
		$aContactUUIDs = [];
		if (!empty($sGroupUUID))
		{
			$aGroupContact = $this->getGroupContacts($sGroupUUID);
			foreach ($aGroupContact as $oGroupContact)
			{
				$aContactUUIDs[] = $oGroupContact->ContactUUID;
			}
			
			if (empty($aContactUUIDs))
			{
				return 0;
			}
		}
		
		return $this->oEavManager->getEntitiesCount(
			Classes\Contact::class,
			$aFilters,
			$aContactUUIDs
		);
	}

	/**
	 * Returns list of contacts within specified range, sorted according to specified requirements. 
	 * 
	 * @param int $iSortField Sort field. Accepted values:
	 *
	 *		\Aurora\Modules\Contacts\Enums\SortField::Name
	 *		\Aurora\Modules\Contacts\Enums\SortField::Email
	 *		\Aurora\Modules\Contacts\Enums\SortField::Frequency
	 *
	 * Default value is **\Aurora\Modules\Contacts\Enums\SortField::Email**.
	 * @param int $iSortOrder Sorting order. Accepted values:
	 *
	 *		\Aurora\System\Enums\SortOrder::ASC
	 *		\Aurora\System\Enums\SortOrder::DESC,
	 *
	 * for ascending and descending respectively. Default value is **\Aurora\System\Enums\SortOrder::ASC**.
	 * @param int $iOffset Ordinal number of the contact item the list stars with. Default value is **0**.
	 * @param int $iLimit The upper limit for total number of contacts returned. Default value is **20**.
	 * @param array $aFilters
	 * @param array $aViewAttrs
	 * 
	 * @return array|bool
	 */
	public function getContacts($iSortField = \Aurora\Modules\Contacts\Enums\SortField::Name, $iSortOrder = \Aurora\System\Enums\SortOrder::ASC,
		$iOffset = 0, $iLimit = 20, $aFilters = array(), $aViewAttrs = array())
	{
		$sSortField = 'FullName';
		switch ($iSortField)
		{
			case \Aurora\Modules\Contacts\Enums\SortField::Email:
				$sSortField = 'ViewEmail';
				break;
			case \Aurora\Modules\Contacts\Enums\SortField::Frequency:
				$sSortField = 'Frequency';
				break;
		}

		$aOrderBy = array($sSortField);
		return $this->oEavManager->getEntities(
			Classes\Contact::class,
			$aViewAttrs, $iOffset, $iLimit, $aFilters, $aOrderBy, $iSortOrder);
	}

	/**
	 * Returns uid list of contacts. 

	 * @param array $aFilters

	 * 
	 * @return array|bool
	 */
	public function getContactUids($aFilters = array())
	{
		return $this->oEavManager->getEntitiesUids(Classes\Contact::class, 0, 0, $aFilters);
	}	

	/**
	 * Returns list of user's groups. 
	 * 
	 * @param int $iUserId User ID 
	 * 
	 * @return array|bool
	 */
	public function getGroups($iUserId, $aFilters = [])
	{
		$aViewAttrs = array();
		if (count($aFilters) > 0)
		{
			$aFilters['IdUser'] = array($iUserId, '=');
			$aFilters = array('$AND' => $aFilters);
		}
		else
		{
			$aFilters = array('IdUser' => array($iUserId, '='));
		}
		$aOrderBy = array('Name');
		return $this->oEavManager->getEntities(
			Classes\Group::class,
			$aViewAttrs, 0, 0, $aFilters, 'Name');
	}

	/**
	 * The method is used for saving created contact to the database. 
	 * 
	 * @param \Aurora\Modules\Contacts\Classes\Contact $oContact
	 * 
	 * @return bool
	 */
	public function createContact($oContact)
	{
		$oContact->DateModified = date('Y-m-d H:i:s');
		$res = $this->oEavManager->saveEntity($oContact);
		
		if ($res)
		{
			foreach ($oContact->GroupsContacts as $oGroupContact)
			{
				$oGroupContact->ContactUUID = $oContact->UUID;
				$this->oEavManager->saveEntity($oGroupContact);
			}
		}

		return $res;
	}

	/**
	 * The method is used for saving created group to the database. 
	 * 
	 * @param \Aurora\Modules\Contacts\Classes\Group $oGroup
	 * 
	 * @return bool
	 */
	public function createGroup($oGroup)
	{
		$res = $this->oEavManager->saveEntity($oGroup);
		
		if ($res)
		{
			foreach ($oGroup->GroupContacts as $oGroupContact)
			{
				$oGroupContact->GroupUUID = $oGroup->UUID;
				$res = $this->oEavManager->saveEntity($oGroupContact);
			}
		}

		return $res;
	}

	/**
	 * Deletes one or multiple contacts from address book.
	 * 
	 * @param array $aContactUUIDs Array of strings
	 * 
	 * @return bool
	 */
	public function deleteContacts($aContactUUIDs)
	{
		$aEntitiesUUIDs = [];
		
		foreach ($aContactUUIDs as $sContactUUID)
		{
			$aEntitiesUUIDs[] = $sContactUUID;
			$aGroupContact = $this->getGroupContacts(null, $sContactUUID);
			foreach ($aGroupContact as $oGroupContact)
			{
				$aEntitiesUUIDs[] = $oGroupContact->UUID;
			}
		}
		
		return $this->oEavManager->deleteEntities($aEntitiesUUIDs);
	}

	public function getGroupContacts($sGroupUUID = null, $sContactUUID = null)
	{
		$aViewAttrs = array('GroupUUID', 'ContactUUID');
		$aFilters = array();
		if (is_string($sGroupUUID) && $sGroupUUID !== '')
		{
			$aFilters = array('GroupUUID' => $sGroupUUID);
		}
		if (is_string($sContactUUID) && $sContactUUID !== '')
		{
			$aFilters = array('ContactUUID' => $sContactUUID);
		}
		return $this->oEavManager->getEntities(
			Classes\GroupContact::class,
			$aViewAttrs, 0, 0, $aFilters);
	}
	
	/**
	 * Deletes specific groups from address book.
	 * 
	 * @param array $aGroupUUIDs array of strings - groups identificators.
	 * 
	 * @return bool
	 */
	public function deleteGroups($aGroupUUIDs)
	{
		$aEntitiesUUIDs = [];
		
		foreach ($aGroupUUIDs as $sGroupUUID)
		{
			$aEntitiesUUIDs[] = $sGroupUUID;
			$aGroupContact = $this->getGroupContacts($sGroupUUID);
			foreach ($aGroupContact as $oGroupContact)
			{
				$aEntitiesUUIDs[] = $oGroupContact->sContactUUID;
			}
		}
		
		return $this->oEavManager->deleteEntities($aEntitiesUUIDs);
	}

	/**
	 * Adds one or multiple contacts to the specific group. 
	 * 
	 * @param string $sGroupUUID Group identifier to be used 
	 * @param array $aContactUUIDs Array of integers
	 * 
	 * @return bool
	 */
	public function addContactsToGroup($sGroupUUID, $aContactUUIDs)
	{
		$res = true;
		
		$aCurrGroupContact = $this->getGroupContacts($sGroupUUID);
		$aCurrContactUUIDs = array_map(
			function($oGroupContact) { 
				return $oGroupContact->ContactUUID; 
			}, 
			$aCurrGroupContact
		);
		
		foreach ($aContactUUIDs as $sContactUUID)
		{
			if (!in_array($sContactUUID, $aCurrContactUUIDs))
			{
				$oGroupContact = \Aurora\Modules\Contacts\Classes\GroupContact::createInstance(
					Classes\GroupContact::class,
					Module::GetName()
				);
				$oGroupContact->GroupUUID = $sGroupUUID;
				$oGroupContact->ContactUUID = $sContactUUID;
				$res = $this->oEavManager->saveEntity($oGroupContact) || $res;
			}
		}
		
		return $res;
	}

	/**
	 * The method deletes one or multiple contacts from the group. 
	 * 
	 * @param string $sGroupUUID Group identifier
	 * @param array $aContactUUIDs Array of integers
	 * 
	 * @return bool
	 */
	public function removeContactsFromGroup($sGroupUUID, $aContactUUIDs)
	{
		$aCurrGroupContact = $this->getGroupContacts($sGroupUUID);
		$aIdEntitiesToDelete = array();
		
		foreach ($aCurrGroupContact as $oGroupContact)
		{
			if (in_array($oGroupContact->ContactUUID, $aContactUUIDs))
			{
				$aIdEntitiesToDelete[] = $oGroupContact->UUID;
			}
		}
		
		return $this->oEavManager->deleteEntities($aIdEntitiesToDelete);
	}
}
