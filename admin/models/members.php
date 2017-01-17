<?php
/**
  * com_pvoks
  * Licence: GNU/GPL
  * Author: Fogler Tibor   
  * Author-email: tibor.fogler@gmail.com
  * Author-web: github.com/utopszkij
  * Verzió: V1.00 
 */

defined("_JEXEC") or die("Restricted access");
include_once JPATH_ADMINISTRATOR.'/components/com_pvoks/models/model.php';

class PvoksModelMembers extends PvoksModel {

	function __construct() {
		parent::__construct();
		$this->componentName = 'pvoks';
		$this->viewName = 'members';
		$this->dbTabla = '#__pvoks_members';
		$this->lngPre ='PVOKS_';
	}
	
	/**
	 * Build an SQL query to load the list data
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()	{
		$db = $this->getDbo();
		$input = JFactory::getApplication()->input;
		$sort = $input->get('filter_order','u.username');
		if ($sort == '') $sort='u.username';
		$order = $input->get('filter_order_Dir','asc');
		if ($order == '') $order = 'asc';
		$input->set('filter_order',$sort);
		$input->set('filter_order_Dir',$order);
		$search = $input->get('filter_str','');
		$filter_category_id = $input->get('filter_category_id',0);
		$filter_user_id = $input->get('filter_user_id',0);
		$search = $input->get('filter_str','');
		$query = $db->getQuery(true);
		$query->select('a.*, u.username, u.name, u.email, c.title');
		$query->from($this->dbTabla.' AS a');
		$query->leftjoin('#__users AS u on u.id = a.user_id');
		$query->leftjoin('#__pvoks_categories as c on c.id = a.category_id');
		// Filter by search
		if (!empty($search)){
			$query->where('((u.username like "%'.$search.'%") or (u.name like "%'.$search.'%") or 
			(c.title like "%'.$search.'%"))');
		}
		if ($filter_category_id > 0) {
			$query->where('(a.category_id = '.$db->quote($filter_category_id).')');
		}
		if ($filter_user_id > 0) {
			$query->where('(a.user_id = '.$db->quote($filter_user_id).')');
		}
		// Add list oredring and list direction to SQL query
		$query->order($db->escape($sort).' '.$db->escape($order));
		//DBG echo $query;
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
		return $result;
	}
	
	/**
	  * rekord tárolható?
	  * @param record  $item 
	  * @return boolean  
	  * ha nem tárolható akkor $this->setError() beállítva 
	*/
	public function check($item) {
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
	  * @return array | false   tabla rekord  | false
	  * hiba estén $this->setError() beállítva 
	*/  
	public function getItem($id=0) {
		$db = JFactory::getDBO();
		$session = JFactory::getSession();
		if ($id > 0) {
		  $db->setQuery('select a.*, u.name, u.username, u.email, c.title 
		  from '.$this->dbTabla.' as a
		  left outer join #__users as u on u.id = a.user_id
		  left outer join #__pvoks_categories as c on c.id = a.category_id
		  where a.id='.$db->quote($id));
		  $result = $db->loadObject();
		  $this->setError($db->getErrorMsg());
		} else {
		  // új rekord inicializálás felvitelhez
		  $user = JFactory::getUser();
		  $result = new stdClass();	
		  $result->id = 0;
		  $result->state = 2;
		  $result->admin = 0;
		}  
		return $result;
	}
	
	/**
	* save a item
	* @param record $item
	* @ return boolean and set $this->error
	*/
	public function save($item) {
		$user = JFactory::getUser();
		if ($item->id == 0) {
		  $item->created_by = $user->id;
		  $item->created = date('Y-m-d H:i:s');
		} else {
		  $item->modified_by = $user->id;
		  $item->modified = date('Y-m-d H:i:s');
		}
		return parent::save($item);
	}
}
?>
