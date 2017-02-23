<?php
/**
* Adatmodellek az EDE_IDE szavazó rendszerhez
* Vote
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*
* EZ MÉG CSAK CSONTVÁZ 
*
*/

require_once (PATH_COMPONENT.'/models/model.php');
require_once (PATH_COMPONENT.'/models/userModel.php');
require_once (PATH_COMPONENT.'/models/rankModel.php');
require_once (PATH_COMPONENT.'/models/memberModel.php');
require_once (PATH_COMPONENT.'/models/groupModel.php');
require_once (PATH_COMPONENT.'/models/choiceModel.php');
require_once (PATH_COMPONENT.'/models/voksModel.php');
require_once (PATH_COMPONENT.'/models/commentModel.php');

/**
* vote data model of EDE_IDE Joomla_PDEngine_plugin
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*/
class PvoksModelVote extends PvoksModel {
	public $id;  // integer ID
	public $group_id; //integer group_id
	public $voteKey; // string vote id in IDE server
	public $title; // str vote short name
	public $desc;  // text vote description
	public $state; // integer vote state PROPOSED | DISQ1 | DISQ2 | VOKS | CLOSED
	public $voteType; // integer vote Type PUBLIC | PUBLIC_LIKQUED | SECRET | SECRET_LIQED 
	public $choicePolicy; // Policy
	public $choiceActiveQuota; // szám | szám% | (expression) | MANUAL
	public $voksPolicy; // Policy
	public $disq1CloseQuota; // szám | szám% | (expression) | MANUAL
	public $disq2CloseQuota; // szám | szám% | (expression) | MANUAL
	public $voksValidQuota; // szám | szám% | (expression) | MANUAL
	public $resultPolicy; // Policy
	public $commentPolicy; // Policy
	public $_group; //
	public $_choices; // RecordSet_of_PvoksModelChoise);
	public $_vokses; // RecordSet_of_PvoksModelVoks);
	public $_comments; // RecordSet_of_PvoksModelComment
	public $_endorses; // RecordSet_of_PvoksModelEndorse

	/**
	* constructor
	*/
	function __construct($group, $vote_id=0) {
		parent::__construct('group');
		$this->_group = $group;
		$this->group_id = $group->id;
		if ($vote_id > 0) $this->load($vote_id);
	}

	/**
	* load object from dataStorage
	* @param int $id
	* @return bool
	*/
	public function load($id) {
		$result = parent::load($id);
		return $result;
	}

	/**
	* init object for add new
	* @return void
	*/
	public function newRecord() {
		parent::newRecord();
	}


	/**
	* store object into dataStorage
	* @return bool
	*/
	public function store() {
		$result = parent::store();
		return $result;
	}

	/**
	* this user is meber of any group' subgroup ?
	*/
	public function isSubMember($user) {
		return $this->_group->isSubMember($user);
	}

	/**
	* this user is meber of this group?
	*/
	public function isMember($user) {
		return $this->_group->isMember($user);
	}

	/**
	* there is rank for this user in this group?
	*/
	public function isMemberRank($user,$rank) {
		return $this->_group->isMemberRank($user);
	}

	/**
	* get voks count this user in this vote
	* @return integer (0 or 1)
	*/
	public function getVoksCountByUser($user) {
		return 0;
	}
} // PvoksModelVote

?>
