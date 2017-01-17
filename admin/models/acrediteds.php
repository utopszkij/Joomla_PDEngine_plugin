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

class PvoksModelAcrediteds extends PvoksModel {

	function __construct() {
		parent::__construct();
		$this->componentName = 'pvoks';
		$this->viewName = 'acrediteds';
		$this->dbTabla = '#__pvoks_acrediteds';
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
		$filter_acredited_id = $input->get('filter_acredited_id',0);
		$query = $db->getQuery(true);
		$query->select('a.*, u.name, b.name as acredited, c.title');
		$query->from($this->dbTabla.' AS a');
		$query->leftjoin('#__users AS u on u.id = a.user_id');
		$query->leftjoin('#__users AS b on b.id = a.acredited_id');
		$query->leftjoin('#__pvoks_categories as c on c.id = a.category_id');
		// Filter by search
		if (!empty($search))
			$query->where('((u.name like "%'.$search.'%") or 
		    (b.name like "%'.$search.'%") or 
			(c.title like "%'.$search.'%"))');
		if ($filter_category_id > 0)
			$query->where('a.category_id = '.$db->quote($filter_category_id));
		if ($filter_user_id > 0)
			$query->where('a.user_id = '.$db->quote($filter_user_id));
		if ($filter_acredited_id > 0)
			$query->where('a.acredited_id = '.$db->quote($filter_acredited_id));
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
		$db = JFactory::getDBO();
		if ($item->acredited_id == 0) {
			$msg .= JText::_('PVOKS_ACREDITED_REQUEST').'<br />';
		}
		if ($item->user_id == 0) {
			$msg .= JText::_('PVOKS_ACREDITED_USER_REQUEST').'<br />';
		}
		if ($item->category_id == 0) {
			$msg .= JText::_('PVOKS_ACREDITED_CATEGORY_REQUEST').'<br />';
		}
		if ($item->acredited_id == $item->user_id) {
			$msg .= JText::_('PVOKS_ACREDITED_WRONG').'<br />';
		}
		
		// rekord egyediség elenörzés
		$db->setQuery('select * 
		from #__pvoks_acrediteds
		where user_id='.$db->quote($item->user_id).' and 
		category_id='.$db->quote($item->categeroy_id).' and
		id <> '.$db->quote($item->id)); 
		$res = $db->loadObject();
		if ($res)
			$msg .= JText::_('PVOKS_ACREDITED_EXISTS').'<br />';
				
		// acredited_id képviselő az adott témakörben?
		$db->setQuery('select * 
		from #__pvoks_members
		where user_id='.$db->quote($item->acredited_id).' and 
		category_id='.$db->quote($item->category_id).' and
		state=3');
		$res = $db->loadObject();
		if ($res == false)
			$msg .= JText::_('PVOKS_ACREDITED_WRONGACREDITED').'<br />';
		
		// átruházási hurok ellenörzés
		$db->setQuery('select u.name 
		from #__users u
		where u.id = '.$item->user_id);
		$res = $db->loadObject();   // $item->user_id 
		$names = array();
		$userIds = array();
		$names[] = $res->name;
		$userIds[] = $item->user_id; 
		$db->setQuery('select a.*, u.name 
		from #__pvoks_acrediteds a, #__users u
		where a.user_id = '.$db->quote($item->acredited_id).' and 
		      a.category_id = '.$db->quote($item->category_id).' and 
			  a.terminate >= '.$db->quote($item->created).' and
			  a.terminate >= '.$db->quote($item->modified).' and
			  u.id = a.user_id ');
		$acr = $db->loadObject();
		
		echo JSON_encode($acr).'<br />';
		
		while ($acr) {
			$names[] = $acr->name;
			if (in_array($acr->user_id, $userIds)) {
				$msg .= JText::_('PVOKS_ACREDITE_CIRCLE').' '.implode(' &gt; ',$names);
				$acr->acredited_id = 0; // break
			}	
			$userIds[] = $acr->user_id;
			$db->setQuery('select a.*, u.name 
			from #__pvoks_acrediteds a, #__users u
			where a.user_id = '.$db->quote($acr->acredited_id).' and 
		      a.category_id = '.$db->quote($item->category_id).' and 
			  a.terminate >= '.$db->quote($item->created).' and
			  a.terminate >= '.$db->quote($item->modified).' and
			  u.id = a.user_id');
			$acr = $db->loadObject();
			
			echo JSON_encode($acr).'<br />';
			
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
		  $db->setQuery('select a.*, u.name, b.name as acredited, c.title 
		  from '.$this->dbTabla.' as a
		  left outer join #__users as u on u.id = a.user_id
		  left outer join #__users as b on b.id = a.acredited_id
		  left outer join #__pvoks_categories as c on c.id = a.category_id
		  where a.id='.$db->quote($id));
		  $result = $db->loadObject();
		  $this->setError($db->getErrorMsg());
		} else {
		  // új rekord inicializálás felvitelhez
		  $user = JFactory::getUser();
		  $result = new stdClass();	
		  $result->id = 0;
		  $result->terminate = date('Y-m-d', time()+(100*24*60*60));
		  $result->user_id = $user->id;
		  $result->acredited_id = 0;
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
		  $item->created = date('Y-m-d H:i:s');
		} else {
		  $item->modified = date('Y-m-d H:i:s');
		}
		return parent::save($item);
	}
}
?>
