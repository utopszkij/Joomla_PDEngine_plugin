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
//include_once JPATH_ADMINISTRATOR.'/components/com_pvoks/models/model.php';
include_once JPATH_COMPONENT.'/models/model.php';

class PvoksModelConfigs extends PvoksModel {

	function __construct() {
		parent::__construct();
		$this->componentName = 'pvoks';
		$this->viewName = 'configs';
		$this->dbTabla = '#__pvoks_configs';
		$this->lngPre ='PVOKS_';
		date_default_timezone_set('Europe/Budapest');
	}
	
	/**
	 * Build an SQL query to load the list data
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()	{
		$db = $this->getDbo();
		$input = JFactory::getApplication()->input;
		$sort = $input->get('filter_order','a.id');
		if ($sort == '') $sort='a.id';
		$order = $input->get('filter_order_Dir','asc');
		if ($order == '') $order = 'asc';
		$input->set('filter_order',$sort);
		$input->set('filter_order_Dir',$order);
		$search = $input->get('filter_str','');
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from($this->dbTabla.' AS a');
		// Filter by search
		if (!empty($search)){
			$query->where('(a.title like "%'.$search.'%")');
		}
		// Add list oredring and list direction to SQL query
		$query->order($db->escape($sort).' '.$db->escape($order));
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
		$db = JFactory::getDBO();
		$db->setQuery('select c.id
		from #__pvoks_configs as c, #__pvoks_categories w
		where c.id = '.$db->quote($id).' and c.config_type="category" and w.category_type = c.id 
		union all
		select c.id
		from #__pvoks_configs as c, #__pvoks_questions w
		where c.id = '.$db->quote($id).' and c.config_type="question" and w.question_type = c.id 
		union all
		select c.id
		from #__pvoks_configs as c
		where c.id = '.$db->quote($id).' and c.config_type="global"		
		');
		$res = $db->loadObjectList();
		if (count($res) > 0) {
			$msg = JText::_($this->lngPrte.'CANNOT_DELETE_IT_IS_USED');
		}
		if ($msg != '') {
			$this->setError($msg);
			$result = false;
		}
		return $result;
	}
	
	/**
	  * rekord tárolható?
	  * @param record  $item 
	  * @return boolean  
	  * ha nem tárolható akkor $this->setError() beállítva 
	*/
	public function check(&$item) {
		$result = true;
		$msg = '';
		$db = JFactory::getDBO();
		if ($item->config_type == '') $msg .= JText::_($this->lngPre.'CONFIG_TYPE_REQUED').'<br />';
		if ($item->title == '') $msg .= JText::_($this->lngPre.'CONFIG_TITLE_REQUED').'<br />';
		if ($item->json == '') $msg .= JText::_($this->lngPre.'CONFIG_JSON_REQUED').'<br />';
		$w = JSON_decode($item->json);
		if ($w == NULL) $msg .= JText::_($this->lngPre.'CONFIG_JSON_ERROR').'<br />';
		if (($item->config_type == 'global') & ($item->id == 0)) {
			$db->setQuery('select id 
			from #__pvoks_configs
			where config_type="global"');
			$res = $db->loadObject();
			if ($res) $msg .= JText::_($this->lngPre.'CONFIG_GLOBAL_ALREDY_EXISTS').'<br />';
		}
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
		  $db->setQuery('select * from '.$this->dbTabla.' where id='.$db->quote($id));
		  $result = $db->loadObject();
		  $this->setError($db->getErrorMsg());
		} else {
		  // új rekord inicializálás felvitelhez
		  $user = JFactory::getUser();
		  $result = new stdClass();	
		  $result->id = 0;
		  $result->config_type = 'global';
		  $result->created_by = $user->id;
		  $result->created = date('Y-m-d H:i:s');
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
