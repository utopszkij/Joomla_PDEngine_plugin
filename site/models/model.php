<?php
/**
* abstract data models of EDE_IDE Joomla_PDEngine_plugin
* RecordSet, Plocy, model
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*/

/**
* recordSet manager object
*/
class RecordSet extends FrameworkInterface {
	protected $model;       // 
	private $filter;      // filter expression
	private $count = -1;  // items count , if not loaded then -1
	private $items = array(); // array of models key=0,1,2...
	private $updated = false;

	/**
	* constructor
	* @param string modelName
	* @param string filter in spec sql string (spec sql syntax ' r.' ' = ')
	* @return RecordSet
	*/
	function __construct($modelName,$filter) {
		parent::__construct();
		$this->model = new $modelName (null);
		$this->filter = $filter;
		$this->count = -1;
		$this->items = array();
	}

	/**
	* get count of record
	* @return integer
	*/
	public function getCount() {
		if ($this->count < 0)
		    $this->count = $this->model->getTotal($this->filter);
		return $this->count;
	}

	/**
	* get one record from recordet
	* @param integer index of record first is 0
	* @return record or false
	*/
	public function item($id) {
		$this->load();
		if (($id >= 0) & ($id < count($this->item))) {
			if (is_object($this->item[$id]))
				$result = $this->item[$id];
			else {
				$modelName = $this->modelName;
				$this->item[$id] = new $modelName(null, $this->item[$id]);
				$result = $this->item[$id];
			}
		} else {
			$result = false;
		}
		return $result;
	}

	/**
	* add one new record into this->items
	* @param record
	* @return bool
	*/
	public function addItem($record) {
		$this->load();
		$this->items[] = $record;
		$this->count++;
		$result = true;
		$this->updated = true;
		return $result;
	}

	/**
	* edit one record in this->items
	* @param record
	* @return bool
	*/
	public function editItem($record) {
		$this->load();
		$id = $record->id;
		if ($id < count($this->items)) {
			$this->items[$id] = $record;
			$this->updated = true;
			$result = false;
		}
		return $result;
	}

	/**
	* delete one record from this->items
	* @param record
	* @return bool
	*/
	public function deleteItem($id) {
		$this->load();
		if ($id < count($this->items)) {
			array_splice($this->items, $id, 1);
			$this->count--;
			$this->updated = true;
			$result = true;
		} else {
			$result = false;
		}
		return $result;
	}

	/**
	* clear all record from this->items
	* @return bool
	*/
	public function clear() {
		$this->items = array();
		$this->count = 0;
		$this->updated = true;
		return true;
	}

	/**
	* load all records from dataStorage into this->items
	* @return void
	*/
	public function load() {
		if (($this->getCount() > 0) & (count($this->items) == 0)) {
			$this->items = $this->model->getIds($this->filter);
			$this->updated = false;
		}
	}

	/**
	* save all records from this->items into dataStorage
	* @return void
	*/
	public function save() {
		if ($this->updated) {
			$this->model->delete($this->filter);
			foreach ($this->items as $item) {
				if (!is_object($item)) {
					$modelName = $this->modelName;
					$this->item[$id] = new $modelName(null, $this->item[$id]);
					$item = $this->item[$id];
				}
				if (is_object($item)) $item->store();
			}
			$this->updated = false;
		}
	}

	/**
	* save all records from this->items into dataStorage (alos for save)
	* @return void
	*/
	public function store() {
		$this->save();
	}

	/**
	* set one value into all records
	* @param string $fieldName
	* @param mixed $value
	* @return void
	*/
	public function setAll($fieldName, $value) {
		for ($i=0; $i < $this->getCount(); $i++)
		  if (is_object($this->item[$i])) $this->item[$i]->$fieldName = $value;
	}

	/**
	* destructor: save records into dataStorage
	*/
	function __destruct() {
		if ($this->updated) {
			$this->save();
		}
	}
} // RecordSet

/**
* accesRight policy
* store in dataStorage: field of group by json_encoded
* by Group
*   $quota1 activate,  $quote2 close, $quota3 archive, $quota4 delete
* by Member, by Rank
*    $quota1 activate,  $quote2 exlude
* by Vote
*   $quota1 activate,  $quote2 abort, $quota3 archive, $quota4 delete,
*   $quota5 dis1Close, $quota6 voksvalid
*/
class Policy {
	protected $parent; // Group or Vote
	protected $quota1; // number | number% | (expression) | MANUAL | PLUGIN
	protected $quota2; // number | number% | (expression) | MANUAL | PLUGIN
	protected $quota3; // number | number% | (expression) | MANUAL | PLUGIN
	protected $quota4; // number | number% | (expression) | MANUAL | PLUGIN
	protected $quota5; // number | number% | (expression) | MANUAL | PLUGIN
	protected $quota6; // number | number% | (expression) | MANUAL | PLUGIN
	public $config; // [{"state":str, "action":str,
		  						//  "area":str, "rank":str, "userGroup":int}]

	/**
	* constructor
	* @param PvoksModelGroup
	* @return Policy
	*/
	function __construct($parent,$json='') {
		$this->parent = $parent;
		$this->quota1 = 0;
		$this->quota2 = 0;
		$this->quota3 = 0;
		$this->quota4 = 0;
		$this->quota5 = 0;
		$this->quota6 = 0;
		if ($json != '')
		  $this->config = JSON_decode($json);
		else
			$this->config = array();
	}

	/**
	* This user can do this action? (see this->group->state to)
	* @param int $action
	* @param User $user
	* @return bool
	*/
	public function canDo($action, $user) {
		$result = false;
		foreach ($this->config as $c) {
			if (($c->action == $action) & ($this->parent->state == $c->state)) {
				$areaJo = false;
				if (($c->area == MEMBER) & ($this->parent->isMember($user))) {
					$areaJo = true;
				} else if (($c->area == SUBMEMBER) & ($this->parent->isSubMember($user))) {
					$areaJo = true;
				} else if ($c->area == NOT_REQUED) {
					$areaJo = true;
				}
				if ($areaJo) {
					 if ($c->rank != NOT_REQUED) {
						 $rankJo = ($this->parent->isMemberRank($user,$rank));
					 } else {
						 $rankJo = true;
					 }
					 if ($rankJo) {
						 $result = ($user->groups[$c->userGroup] == $c->userGroup);
					 }
				} // areaJo
			} // action & group->state
		}
		return $result;
	}
} // Policy

/**
* abstract data model
*/
class PvoksModel extends FrameWorkInterface {
	protected $tableName = '';
	protected $msg = '';

	/**
	* contructor
	* @param string tableName
	* @return PvoksModel
	*/
	function __construct($tableName, $id=0) {
		parent::__construct();
		$this->tableName = $tableName;
		if ($id > 0) $this->load($id);
	}

	public function load($id) {
		$item = $this->ds->getItem($this->tableName, $id);
		if ($item) {
		  foreach ($item as $fn => $fv) $this->$fn = $fv;
		  $result = true;
		} else {
		  $result = false;
		  $msg = $this->tableName.' '.$this->_('LOAD_ERROR');
		}
		return $result;
  }

	public function store() {
		$result = true;
		$item = new stdClass();
		foreach ($this as $fn => $fv) {
			if (substr($fn,0,1) != '_')
			   $item->$fn = $fv;
		}
    return $result;
	}

	/**
	* init new record for add
	* @return void
	*/
	public function newRecord() {
		$this->id = 0;
	}


	/* record validation for add or edit
	* @return bool
	*/
	public function check()  {
		return $result;
  }

	/**
	* check can delete this record
	* @return bool
	*/
	public function canDelete() {
		$this->msg = '';
	}

	/**
	* read records
	* @param string filter sql syntax can ' r.' and ' = '
	* @param string order sql syntax or ''
	* @param integer limit def.20
	* @param integer limitstart def.0
	* @return array_of_record
	*/
	public function getItems($filter, $order='', $limit=20, $limitstart = 0) {
		return $this->ds->getItems($this->tableName, $filter, $order, $limit, $limitstart);
	}

 /**
	* get total record count by filter
	* @param string filter sql syntax can ' r.' and ' = '
	* @return integer
	*/
	public function getTotal($filter) {
		return $this->ds->getCount($this->tableName, $filter);
	}

	public function __destruct() {
	  parent::__destruct();
	}

}

?>
