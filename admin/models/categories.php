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
include_once JPATH_COMPONENT.'/models/model.php';

class PvoksModelCategories extends PvoksModel {

	function __construct() {
		parent::__construct();
		$this->componentName = 'pvoks';
		$this->viewName = 'categories';
		$this->dbTabla = '#__pvoks_categories';
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
		//DBG echo $query;
		return $query;
	}
	
	/**
	* get categories order by tree structure  recursive function
	*/
	protected function getTree(&$result, $parent, $s) {
		$db = $this->getDBO();
		$db->setQuery('select id,title,alias,state
		from #__pvoks_categories
		where parent_id = '.$db->quote($parent).'
		order by 2');
		$res = $db->loadObjectList();
		foreach ($res as $res1) {
			$res1->title = $s.$res1->title;
			$res1->alias = '';
			$result[] = $res1;
			$this->getTree($result, $res1->id, $s.'- ');
		}
	}
	
	/**
	* load record set
	* @input string $order
	* @input string filter_str
	*/
	public function getItems() {
		$db = $this->getDBO();
		$input = JFactory::getApplication()->input;
		$sort = $input->get('filter_order','id');
		if ($sort == 'a.tree') {
			$input->set('limitstart',0);
			$result = array();
			$result[] = JSON_decode('{"id":0,"title":"root","alias":"","state":"1"}'); 
			$this->getTree($result,0,'- ');
			return $result;	
		} else {
			return parent::getItems();
		}
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
		$db->setQuery('select q.id
		from #__pvoks_questions as q
		where q.category_id = '.$db->quote($id).'
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
		$item->alias = trim($item->alias);
		if ($item->alias == '') {
		  $item->alias = iconv("UTF-8", "ASCII//IGNORE", $item->title);
		  $item->alias = iconv("ASCII", "UTF-8", $item->alias);
		}  
		$item->alias = str_replace(' ','-',$item->alias);
		$item->alias = str_replace('_','-',$item->alias);
		$item->alias = str_replace('?','-',$item->alias);
		$item->alias = str_replace('&','-',$item->alias);
		$item->alias = str_replace('.','-',$item->alias);
		$item->alias = str_replace(':','-',$item->alias);
		$item->alias = str_replace('@','-',$item->alias);
		$item->alias = str_replace('--','-',$item->alias);
		$item->alias = str_replace('---','-',$item->alias);
		
		if ($item->category_type == '') $msg .= JText::_($this->lngPre.'CATEGORIES_TYPE_REQUED').'<br />';
		if ($item->title == '') $msg .= JText::_($this->lngPre.'CATEGORIES_TITLE_REQUED').'<br />';
		if ($item->id > 0)
		  $db->setQuery('select id from #__pvoks_categories where alias='.$db->quote($item->alias).' and id<>'.$db->quote($item->id));
		else
		  $db->setQuery('select id from #__pvoks_categories where alias='.$db->quote($item->alias));
		$res = $db->loadObjectList();
		if (count($res) > 0) $msg .= JText::_($this->lngPre.'ALIAS_USED').'<br />';
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
		  $result->text = $result->introtext.'<hr id="system-readmore" />'.$result->fulltext;
		} else {
		  // új rekord inicializálás felvitelhez
		  $user = JFactory::getUser();
		  $result = new stdClass();	
		  $result->id = 0;
		  $result->category_type = 0;
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
		$i = mb_stripos($item->text,'<hr id="system-readmore" />');
		if ($i > 0) {
			$item->introtext = mb_substr($item->text,0, $i);
			$item->fulltext = mb_substr($item->text,$i+27, 20000);
		} else {
			$item->introtext = $item->text;
			$item->fulltext = '';
		}
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
	
	/**
	* delete records
	* @param array of integer $ids
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
			  $db->setQuery('delete  from #__pvoks_members where category_id='.$db->quote($id));
			  $db->query();
			  $db->setQuery('delete  from #__pvoks_acrediteds where category_id='.$db->quote($id));
			  $db->query();
			  $result = ($db->getErrorNum() == 0);
			  $msg = $this->getError();
			  if ($db->getErrorNum() > 0) {
					$this->setError($msg.'<br />'.$db->getErrorMsg());
			  }
		}	
		return $result;
	}
}
?>
