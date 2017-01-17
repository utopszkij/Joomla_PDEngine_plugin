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

class PvoksModelQuestions extends PvoksModel {

	function __construct() {
		parent::__construct();
		$this->componentName = 'pvoks';
		$this->viewName = 'questions';
		$this->dbTabla = '#__pvoks_questions';
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
		$query->select('a.*, c.title AS ctitle');
		$query->from($this->dbTabla.' AS a');
		$query->leftjoin('#__pvoks_categories AS c on c.id=a.category_id');
		
		// Filter by search
		if (!empty($search)){
			$query->where('(a.title like "%'.$search.'%")');
		}
		if ($filter_category_id > 0) {
			$query->where('(a.category_id = '.$db->quote($filter_category_id).')');
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
		$msg = '';
		// ha nem törölhető: $msg .= 'hibaüzenet<br />'; $result = false;
		$db = JFactory::getDBO();
		$db->setQuery('select q.id
		from #__pvoks_options as q
		where q.question_id = '.$db->quote($id).'
		union all
		select q.id
		from #__pvoks_voters as q
		where q.question_id = '.$db->quote($id).'
		union all
		select q.id
		from #__pvoks_votes as q
		where q.question_id = '.$db->quote($id).'
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
		
		if ($item->question_type == '') $msg .= JText::_($this->lngPre.'QUESTION_TYPE_REQUED').'<br />';
		if ($item->title == '') $msg .= JText::_($this->lngPre.'QUESTION_TITLE_REQUED').'<br />';
		if ($item->category_id <= 0) $msg .= JText::_($this->lngPre.'QUESTION_CATEGORY_REQUED').'<br />';
		
		if ($item->id > 0)
		  $db->setQuery('select id from #__pvoks_questions where alias='.$db->quote($item->alias).' and id<>'.$db->quote($item->id));
		else
		  $db->setQuery('select id from #__pvoks_questions where alias='.$db->quote($item->alias));
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
		  $db->setQuery('select * from #__pvoks_categories where id = '.$db->quote($result->category_id));
		  $result->category = $db->loadObject();
		  $db->setQuery('select * from #__pvoks_configs where id = '.$db->quote($result->question_type));
		  $result->qtype = $db->loadObject();
		  if ($result->qtype->json != '')
		    $result->qtype->json = JSON_decode($result->qtype->json);
		} else {
		  // új rekord inicializálás felvitelhez
		  $user = JFactory::getUser();
		  $result = new stdClass();	
		  $result->id = 0;
		  $result->question_type = 1;
		  $result->category_type = 0;
		  $result->publicvote = 0;
		  $result->acrediteenabled = 0;
		  $result->state = 0;
		  $result->optvalid='voterCount * 0.1';
		  $result->votevalid='voterCount * 0.5';
		  $result->termins=date('Y-m-d', time()+30*24*60*60).',
'.date('Y-m-d', time()+60*24*60*60).',
'.date('Y-m-d', time()+120*24*60*60).',
'.date('Y-m-d', time()+150*24*60*60).',
'.date('Y-m-d', time()+180*24*60*60);
		  $result->created_by = $user->id;
		  $result->created = date('Y-m-d H:i:s');
		  $result->category = new stdClass();
		  $result->qtype = new stdClass();
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
		if ($item->taget_category_id <= 0) $item->taget_category_id = $item->category_id;
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
			  $db->setQuery('delete  from #__pvoks_options where question_id='.$db->quote($id));
			  $db->query();
			  $db->setQuery('delete  from #__pvoks_voters where question_id='.$db->quote($id));
			  $db->query();
			  $db->setQuery('delete  from #__pvoks_votes where question_id='.$db->quote($id));
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
