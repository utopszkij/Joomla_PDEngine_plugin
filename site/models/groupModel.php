<?php
/**
* Adatmodellek az EDE_IDE szavazó rendszerhez
* Group
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*/

require_once (PATH_COMPONENT.'/models/model.php');
require_once (PATH_COMPONENT.'/models/userModel.php');
require_once (PATH_COMPONENT.'/models/rankModel.php');
require_once (PATH_COMPONENT.'/models/memberModel.php');
require_once (PATH_COMPONENT.'/models/voteModel.php');
require_once (PATH_COMPONENT.'/models/endorseModel.php');
require_once (PATH_COMPONENT.'/models/commentModel.php');

// tisztség betöltésének feltételei, area kodok
define ('NOT_REQUED',0);
define ('MEMBER',1);
define ('SUBMEMBER',2);

// csoport tagság, tisztség, group állapotok
define ('CANDIDATED',-2);
define ('INVITED',-1);  // csak csoport tagsághoz
define ('PROPOSED',0);
define ('ACTIVE',1);
define ('CLOSED',2);
define ('EXLUDED',3);

// action kodok
define ('ADD',0);  // subGroup, member, member' Rank, vote, comment
define ('PROPOSE_ADD',1); // subGroup, member, member' Rank, vote
define ('ENDORSE_ADD',2); // subGroup, member, member' Rank, vote
define ('ACTIVATE',3);     // subGroup, member, member' Rank, vote, comment
define ('EDIT',10); // subGroup, member, member' Rank, vote, comment
define ('CLOSE',20); // subGroup, member, member' Rank
define ('PROPOSE_CLOSE',21); // subGroup, member, member' Rank
define ('ENDORSE_CLOSE',22); // subGroup, member, member' Rank
define ('REMOVE',30); // subGroup, member, member' Rank, Vote, comment
define ('PROPOSE_REMOVE',31); // subGroup, member, member' Rank, Vote, comment
define ('ENDORSE_REMOVE',32); // subGroup, member, member' Rank, Vote, comment
define ('ARCHIVE',40); // subGroup, member, member' Rank, Vote, comment
define ('PROPOSE_ARCHIVE',41); // subGroup, member, member' Rank, Vote, comment
define ('ENDORSE_ARCHIVE',42); // subGroup, member, member' Rank, Vote, comment
define ('INVITE',62); // member
define ('CANDIDATE',62); // member
define ('EXLUDE',70); // member
define ('PROPOSE_EXLUDE',70); // member
define ('ENDORSE_EXLUDE',70); // member

// group types
define ('DUMBER',0);
define ('DUMBER_META',1);
define ('ADHOC',2);
define ('ADHOC_META',3);

/**
* group data model of EDE_IDE Joomla_PDEngine_plugin
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*/
class PvoksModelGroup extends PvoksModel {
	protected $_parent;
	public $id;  // ID
	public $parent_id;
	public $title; // group short name
	public $desc;  // group description
	public $state; // group state PROPOSED | ACTIVE | CLOSED
	public $groupType; // DUMBER | DUMBER_META | ADHOC | ADHOC_META
	public $memberPolicy; // Policy
	public $rankPolicy; // Policy
	public $subGroupPolicy; // Policy
	public $votePolicy; // Policy
	public $commentPolicy; // Policy
	public $_ranks; // RecordSet_of_PvoksModelRank
	public $_members; // RecordSet_of_Pv);
	public $_subGroups; // RecordSet_of_PvoksModelGroup
	public $_votes; // RecordSet_of_PvoksModelVote
	public $_comments; // RecordSet_of_PvoksModelComment
	public $_endorses; // RecordSet_of_PvoksModelEndorse

	/**
	* constructor
	* @return PvoksModelGroup
	*/
	function __construct($parent, $group_id=0) {
		parent::__construct('group');
		$this->_parent = $parent;
		$this->id = 0;
		$this->parent_id = $parent->id;
		$this->title = '';
		$this->desc = '';
		$this->state = PROPOSED;
		$this->groupType = ADHOC;
		$this->memberPolicy = new Policy($this,'');
		$this->rankPolicy = new Policy($this,'');
		$this->subGroupPolicy = new Policy($this,'');
		$this->votePolicy = new Policy($this,'');
		$this->commentPolicy = new Policy($this,'');
		$this->_ranks = new RecordSet('PvoksModelRank',' r.group_id = '.$this->id);
		$this->_members = new RecordSet('PvoksModelMember',' r.group_id = '.$this->id);
		
		// végtelen ciklusba kerül! - ezért késleltetten (amikor szükség lesz rá) lesz létrehozva.
		// $this->_subGroups = new RecordSet('PvoksModelGroup',' r.parent_id = '.$this->id);
		$this->_subGroups = false;
		
		$this->_votes = new RecordSet('PvoksModelVote',' r.group_id = '.$this->id);
		$this->_comments = new RecordSet('PvoksModelComment',' r.objectType = "group" and r.object_id = '.$this->id);
		$this->_endorses = new RecordSet('PvoksModelEndorse',' r.objectType = "group" and r.object_id = '.$this->id);
		if ($group_id > 0) $this->load($group_id);
	}

	/**
	* load object from dataStorage
	* @param int $id
	* @return bool
	*/
	public function load($id) {
		$result = parent::load($id);
		if ($result) {
			$this->memberPolicy = new Policy($this,$this->memberPolicy);
			$this->rankPolicy = new Policy($this,$this->memberPolicy);
			$this->subGroupPolicy = new Policy($this,$this->memberPolicy);
			$this->votePolicy = new Policy($this,$this->memberPolicy);
			$this->commentPolicy = new Policy($this,$this->memberPolicy);
			$this->_ranks = new RecordSet('PvoksModelRank',' r.group_id = '.$this->id);
			$this->_members = new RecordSet('PvoksModelMember',' r.group_id = '.$this->id);
			$this->_subGroups = new RecordSet('PvoksModelGroup',' r.parent_id = '.$this->id);
			$this->_votes = new RecordSet('PvoksModelVote',' r.group_id = '.$this->id);
			$this->_comments = new RecordSet('PvoksModelComment',' r.objectType = "group" and r.object_id = '.$this->id);
			$this->_endorses = new RecordSet('PvoksModelEndorse',' r.objectType = "group" and r.object_id = '.$this->id);
		}
		return $result;
	}

	/**
	* init object for add new record
	* @return void
	*/
	public function newRecord() {
		$this->id = 0;
		$this->title = '';
		$this->desc = '';
		$this->state = PROPOSED;
		$this->groupType = ADHOC;
		$this->memberPolicy = new Policy($this,'');
		$this->rankPolicy = new Policy($this,'');
		$this->subGroupPolicy = new Policy($this,'');
		$this->votePolicy = new Policy($this,'');
		$this->commentPolicy = new Policy($this,'');
		$this->_ranks = new RecordSet('PvoksModelRank',' r.group_id = '.$this->id);
		$this->_members = new RecordSet('PvoksModelMember',' r.group_id = '.$this->id);
		$this->_subGroups = new RecordSet('PvoksModelGroup',' r.parent_id = '.$this->id);
		$this->_votes = new RecordSet('PvoksModelVote',' r.group_id = '.$this->id);
		$this->_comments = new RecordSet('PvoksModelComment',' r.objectType = "group" and r.object_id = '.$this->id);
		$this->_endorses = new RecordSet('PvoksModelEndorse',' r.objectType = "group" and r.object_id = '.$this->id);
	}


	/**
	* store object into dataStorage
	* @return bool
	*/
	public function store() {
		if (!is_object($this->_subGroups))
		  $this->_subGroups = new RecordSet('PvoksModelGroup',' r.parent_id = '.$this->id);
		
		$this->memberPolicy = JSON_encode($this->memberPolicy->config);
		$this->rankPolicy = JSON_encode($this->rankPolicy->config);
		$this->subGroupPolicy = JSON_encode($this->subGroupPolicy->config);
		$this->votePolicy = JSON_encode($this->votePolicy->config);
		$this->commentPolicy = JSON_encode($this->commentPolicy->config);
		$result = parent::store();
		if ($result) {
			$this->_ranks->setAll('group_id',$this->id);
			$this->_ranks->save();
			$this->_members->setAll('group_id',$this->id);
			$this->_members->save();
			$this->_subGroups->setAll('group_id',$this->id);
			$this->_subGroups->save();
			$this->_votes->setAll('group_id',$this->id);
			$this->_votes->save();
			$this->_comments->setAll('objectType','group');
			$this->_comments->setAll('object_id',$this->id);
			$this->_comments->save();
			$this->_endorses->setAll('objectType','group');
			$this->_endorses->setAll('object_id',$this->id);
			$this->_endorses->save();
		}
		return $result;
	}

	/**
	* this user is meber of any group' subgroup ?
	* @param User $user
	* @return void
	*/
	public function isSubMember($user) {
		if (!is_object($this->_subGroups))
		  $this->_subGroups = new RecordSet('PvoksModelGroup',' r.parent_id = '.$this->id);
		$result = false;
		for ($i=0; $i < $this->_subGroups->getCount(); $i++) {
			$subGroup = $this->subGroups->item($i);
			if ($subGroups->isMember($user))
				$result = true;
			if (($result == false) & ($subGroup->_subGroups->getCount() > 0))
				$result = $subGroup->isSubMember($user);
		}
		return $result;
	}

	/**
	* this user is meber of this group?
	* @param User $user
	* @return bool
	*/
	public function isMember($user) {
		$result = false;
		for ($i=0; $i < $this->_members->getCount(); $i++) {
			$member = $this->_members->item($i);
			if (($member->id == $user->id) & ($member->state == ACTIVE)) $result = true;
		}
		return $result;
	}

	/**
	* there is rank for this user in this group?
	* @param User $user
	* @param string $rankTitle
	* @return bool
	*/
	public function isMemberRank($user,$rankTitle) {
		$result = false;
		for ($i=0; $i < $this->_members->getCount(); $i++) {
			$member = $this->_members->item($id);
			if (($member->id == $user->id) &
			    ($member->state == ACTIVE) &
					($member->ranks[$rankTitle] == ACTIVE)) $result = true;
		}
		return $result;
	}

	/**
	* get list of user by rank for this group
	* @param string $rankTitle
	* @return Array_of_User
	*/
	public function getMembersByRank($rankTitle) {
		$result = array();
		for ($i=0; $i < $this->_members->getCount(); $i++) {
			$member = $this->_members->item($i);
			if ($member->ranks[$rankTitle] == ACTIVE) {
				$result[] = new PvoksModelUser($member->user_id);
			}
		}
		return $result;
	}

	/**
	* get sended voks count for this user in this group
	* @param User $user
	* @return integer
	*/
	public function getVoksCountByUser($user) {
		if (!is_object($this->_subGroups))
		  $this->_subGroups = new RecordSet('PvoksModelGroup',' r.parent_id = '.$this->id);
		$result = 0;
		for ($i=0; $i < $this->_votes->getCount(); $i++) {
			$vote =$this->_votes->item($i);
			$result = $result + $vote->getVoksCountByUser($user);
		}
		for ($i=0; $i < $this->_subGroups->getCount(); $i++) {
			$subGroup = $this->_subGroups->item($i);
			$result = $result + $subGroup->getVoksCountByUser($user);
		}
		return $result;
	}

} // PvoksModelGroup

?>
