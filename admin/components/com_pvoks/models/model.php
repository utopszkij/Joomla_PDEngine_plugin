<?php
/**
 * pvoks component nezet view data model
 * @author  Fogler Tibor		
 * @copyright	Nincs
 * @license		GNU/GPL
 * replace 'pvoks' --> controllerName
 *         'Pvoks' --> ControllerName
 *         'ALAP' --> CONTROLLER_NAME 
 */

defined("_JEXEC") or die("Restricted access");

//+ overWite this code in PvoksModelNezet ==============================================================

class PvoksModel extends JModelList {
    	protected $componentName = 'pvoks'; // controllers/pvoks.php -ben PvoksControllerNezet class
	protected $viewName;                //views/view.html.php -ben PvoksViewNezet class, és js/nezet.js és models/forms/nezet.xml 
	protected $lngPre;                  // leng pre
	protected $dbTabla = '#__nezet';	

	function __create() {
		parent::create();
		$this->viewName = 'viewName';
		$this->lngPre ='PVOKS_';
	}
	
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()	{
		$db = $this->getDbo();
		$input = JFactory::getApplication()->input;
		$sort = $input->get('filter_order',1);
		$order = $input->get('filter_order_DIR','asc');
		$search = $input->get('filter_str','');
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from($this->dbTabla.' AS a');
		// Filter by search
		if (!empty($search)){
			$query->where('(a.name like "%'.$search.'%")');
		}
		// Add list oredring and list direction to SQL query
		$query->order($db->escape($sort).' '.$db->escape($order));
		// echo $query.'<br />';
		return $query;
	}
	
	/**
	  * rekord törölhető?
	  * @param integer  $id  rekord azonosító
	  * @return boolean  
	  * ha nem törölhető akkor $this->setError() beállítva 
	*/
	public function canDelete($id) {
		$result = true;
		$msg = '';
		// ha nem törölhető: $msg .= 'hibaüzenet<br />'; $result = false;
		if ($msg != '') {
			$this->setError($msg);
			$result = false;
		}
		return $result;
	}
	
	/**
	  * rekord tárolható?
	  * @param integer  $item  rekord 
	  * @return boolean  
	  * ha nem tárolható akkor $this->setError() beállítva 
	*/
	public function check(&$item) {
		$result = true;
		$msg = '';
		// ha nem jó: $msg .= 'hibaüzenet<br />'; $result = false;
		if ($msg != '') {
			$this->setError($msg);
			$result = false;
		}
		return $result;
	}

	/**
	  * egy rekord beolvasása, illetve ha id=0 rekord init felvitelhez
	  * @param integer $id  rekord egyedi azonsító
	  * @return array | false   tabla rekord  | falsestrtolower
	  * hiba estén $this->setError() beállítva 
	*/  
	public function getItem($id=0) {
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		if ($id > 0) {
		  $db->setQuery('select * from '.$this->dbTabla.' where id='.$db->quote($id));
		  $result = $db->loadObject();
		  $this->setError($db->getErrorMsg());
		} else {
		  // új rekord inicializálás felvitelhez
		  $user = JFactory::getUser();
		  $result = new stdClass();	
		  $result->id = 0;
		}  
		return $result;
	}
//- overWrite =====================================================================================
	
		
	public function getTable($type = '', $prefix = '', $config = array())	{
		if ($type == '') $type = ucFirst($this->viewName);
		if ($prefix == '') $prefix = ucFirst($this->componentName).'Table';
		$this->addTablePath(JPATH_COMPONENT . '/tables'); 
		return JTable::getInstance($type, $prefix, $config);
	} 	

	/**
	  * ellenörzött record tárolás (insert or update)
	  * @param array  $item  tabla rekord
	  * @return boolean  sikeres vagy nem?
	  * hivja a $this->check metodust
	  * hiba estén $this->setError() beállítva 
	*/
	public function save($item) {
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		if ($this->check($item)) {
			$table = $this->getTable();
			$table->bind($item);
			$result = $table->store();
		} else {
			$result = false;
		}
		return $result;
	}
	
	/**
	  * ellenörzött rekord vagy rekord set törlés (insert orstrtolower update)
	  * @param array  $ids  rekord azonosítók
	  * @return boolean  sikeres vagy nem?
	  * hiba estén $this->setError() beállítva 
	*/
	public function delete($ids) {
		$result = true;
		$db = JFactory::getDBO();
		if (is_array($ids) == false)  {
		  $ids = array($ids);
		}
		foreach ($ids as $id) {
		  if ($this->canDelete($id) == false) $result = false;
		}	  
		if (! $result) return false;
		foreach ($ids as $id) {
			  $item = $this->getItem($id);	
			  $db->setQuery('delete  from '.$this->dbTabla.' where id='.$db->quote($id));
			  $db->query();
			  $result = ($db->getErrorNum() == 0);
			  $msg = $this->getError();
			  if ($db->getErrorNum() > 0) {
					$this->setError($msg.'<br />'.$db->getErrorMsg());
			  }
		}	
		return $result;
	}
	
	//inherited
	//getTotal();
	//getPagination();
	//getItems();
	//getname()
}
?>
