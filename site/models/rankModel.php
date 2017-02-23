<?php
/**
* Adatmodel az EDE_IDE szavazó rendszerhez
* Rank az egyes csoportokban használt tisztségek definiálása
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*/

require_once (PATH_COMPONENT.'/models/model.php');
require_once (PATH_COMPONENT.'/models/userModel.php');
require_once (PATH_COMPONENT.'/models/groupModel.php');

/**
* az egyes kategóriákban megengedett tag tisztségek definiálása
* default eleme minden kategóriában:
*/
class PvoksModelRank extends PvoksModel {
	protected $_group; // ehez a csoporthoz tartozik
	public $group_id;  // ehez a csoporthoz tartozik
	public $id = 0;    // egyedi azonosító
	public $title = ''; // rövid megnevezés
	public $desc = ''; // hosszabb leírás
	public $inMember = 0; // NOT_REQUED | MEMBER | SUBMEMBER
	public $userGroups = array(); // userGroup lista

	function __construct($group, $rank_id = 0) {
		parent::__construct('PvoksModelRank');
		$this->_group = $group;
		$this->group_id = $group->id;
		if ($rank_id > 0) $this->load($rank_id);
	}

	/**
	* get one record from recordet
	* @param integer index of record first is 0
	* @return record or false
	*/
	public function item($id) {
		$result = parent::item($id);
		$result->userGroups = JSON_decode($result->userGroups);
		return $result;
	}

	/**
	* save all records from this->items into dataStorage
	* @return void
	*/
	public function save() {
		if ($this->updated) {
			$this->dataStorage->delete($this->tableName,$this->filter);
			foreach ($this->items as $item) {
				$item->userGroups = JSON_encode($item->userGroups);
				$this->dataStorage->add($this->tableName,$item);
			}
			$this->updated = false;
		}
	}


	/**
	* ellenörzés felvitelhez és modositáshoz.
	* title a kategorián belül egyedi legyen, és nem lehet üres
	* groups nem lehet üres set
	*/
	public function check() {
		$this->msg = '';
		if ($this->title == '') $this->msg = $this-__('RANK_REQUED').'<br />';
		$j = $this->_group->_ranks->getCount();
		for ($i = 0; $i < $j; $i++) {
			if (($this->_group->_ranks->item($i)->title == $this->title) &
			    ($this->_group->_ranks->item($i)->id != $this->id))
				$this->msg .= $this->_('RANK_EXISTS').'<br />';
		}
		if (count($this->userGroups) == 0) $this->msg .= $this->_('MIN_ONE_USERGROUP_REQUEST').'<br />';
		return ($msg == '');
	}

	/**
	* ellenörzés: törölhető?
	* ha van hozzá user akkor nem törölhető
	*/
	public function canDelete() {
		$this->msg = '';
		$users = $this->_group->getMembersByRank($this->title);
		if ($users->getCount() > 0) $this->msg .= $this->_('RANK_IS_USED').'<br />';
		return ($msg == '');
	}

    /**
	* init new record for add
	* @return void
	*/
	public function newRecord() {
		$this->id = 0;
		$this->title = '';
		$this->desc = '';
		$this->inMember = NOT_REQUED;
		$this->userGroups = array();
	}
}

?>
