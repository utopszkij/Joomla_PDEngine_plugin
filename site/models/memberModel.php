<?php
/**
* Adatmodel az EDE_IDE szavazó rendszerhez
* Member  csoport tagok
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*/

require_once (PATH_COMPONENT.'/models/model.php');
require_once (PATH_COMPONENT.'/models/groupModel.php');
require_once (PATH_COMPONENT.'/models/userModel.php');

/**
* csoport tagok, tisztségviselők
*/
class PvoksModelMember extends PvoksModel {
	protected $_group = false;
	protected $_user = false;
	public $id = 0; // egyedi azonositó
	public $group_id = 0; // csoport azonosító
	public $user_id = 0;  // user azonosító
	public $state = 0;  // tagság státusza PROPOSED | ACTIVE | CLOSED | EXLUDED
	public $ranks = array(); // key:rankTitle, value:rankState

	function __construct($group,$member_id=0) {
		parent::__construct('members');
		$this->_group = $group;
		$this->_group_id = $group_id;
		if ($member_id > 0) $this->load($member_id);
	}

	/**
	* ellenörzés felvitelhez és modositáshoz.
	* group_id, user_id kötelező
	* state adott választék
	* ranks_title ellenörzése
	* ranks_state ellenörzés
	* ranks feltételeknek megfelel a user?
	*/
	public function check() {
		$this->msg = '';
		if ($this->group_id <= 0) $this->msg .= $this->_('GROUP_REQUED').'<br />';
		if ($this->user_id <= 0) $this->msg .= $this->_('USER_REQUED').'<br />';
		if (($this->state != CANDIDATED) &
           	 ($this->state != INVITED) &
	        	 ($this->state != PROPOSED) &
           	 ($this->state != ACTIVE) &
	        	 ($this->state != CLOSED) &
	       		 ($this->state != EXLUDED))
			 $this->msg .= $this->_('WRONG_MEMBER_SATE').'<br />';

		foreach ($this->ranks as $rankTitle => $rankState) {
			// az adott csoportban használt tisztség kód?
			$jo = false;
			for ($i=0; $i<$this->_group->_ranks->getCount(); $i++) {
				$grank = $this->_group->_ranks->item($i);
				if ($grank->title == $rankTitle) $jo = true;
			}
			if (!$jo) $this->msg .= $this->_('WRONG_RANK').'<br />';
			if (($rankState != PROPOSED) &
			    ($rankState != ACTIVE) &
			    ($rankState != CLOSED))
				 $this->msg .= $this->_('WRONG_RANK_SATE').'<br />';
		} // foreach
	} //check

	/**
	* ellenörzés: törölhető?
	* ha van tisztsége akkor nem törölhető
	* ha van leadott szavazata akkor nem törölhető
	*/
	public function canDelete() {
		foreach ($this->ranks as $rankTitle => $rankState) {
			// user megfelel a tisztség feltételeinek?
			$userModel = new PvoksModelUser();
			$user = $userModel->load($this->user_id);
			for ($i=0; $i<$this->_group->_ranks->getCount(); $i++) {
				$jo = false;
				$grank = $this->_group->_ranks->item($i);
				if ($grank->title == $rankTitle) {
					if ($grank->inMember == MEMBER) {
						if ($this->_group->isMember($user)) $jo = true;
					} else if ($grank->inMember == SUBMEMBER) {
						if ($this->_group->isSubMember($user)) $jo = true;
					} else {
						$jo = true;
					}
					if (!$jo) $this->msg .= $this->_(USER_CAN_NOT_THIS_RANK).'<br />';
				}
				$jo = false;
				foreach ($grank->userGroups as $gUserGroup) {
					if (in_array($gUserGroup, $user->groups)) $jo = true;
				}
				if (!$jo) $this->msg .= $this->_(USER_CAN_NOT_THIS_RANK).'<br />';
			} // for $this->_group->ranks
		} //foreach $this->ranks
		return ($msg == '');
	}

  	/**
	* init new record for add
	* @return void
	*/
	public function newRecord() {
		$this->id = 0;
		$this->user_id = 0;
		$this->state = 0;
		$this->ranks = array();
		$this->_users = new RecordSet('meber',' r.group_id = '.$this->group_id);
	}

	public function load($id) {
		parent::load($id);
		$this->_users = new RecordSet('meber',' r.group_id = '.$this->group_id);
	}

} // PvoksModelMember

?>
