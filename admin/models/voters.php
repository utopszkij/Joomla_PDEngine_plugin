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

class PvoksModelVoters extends PvoksModel {

	function __construct() {
		parent::__construct();
		$this->componentName = 'pvoks';
		$this->viewName = 'voters';
		$this->dbTabla = '#__pvoks_voters';
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
		$filter_question_id = $input->get('filter_question_id',0);
		$query = $db->getQuery(true);
		$query->select('a.*, u.name, q.title');
		$query->from($this->dbTabla.' AS a');
		$query->leftjoin('#__pvoks_questions AS q ON q.id = a.question_id');
		$query->leftjoin('#__users AS u ON u.id = a.user_id');
		// Filter by search
		if (!empty($search)){
			$query->where('(u.name like "%'.$search.'%")');
		}
		if ($filter_question_id > 0) {
			$query->where('a.question_id = '.$db->quote($filter_question_id));
		}
		// Add list oredring and list direction to SQL query
		$query->order($db->escape($sort).' '.$db->escape($order));
		//DBG echo $query;
		return $query;
	}
}
?>
