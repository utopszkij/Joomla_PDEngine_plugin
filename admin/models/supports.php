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

class PvoksModelSupports extends PvoksModel {

	function __construct() {
		parent::__construct();
		$this->componentName = 'pvoks';
		$this->viewName = 'supports';
		$this->dbTabla = '#__pvoks_supports';
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
		$sort = $input->get('filter_order','1');
		if ($sort == '') $sort='1';
		$order = $input->get('filter_order_Dir','asc');
		if ($order == '') $order = 'asc';
		$input->set('filter_order',$sort);
		$input->set('filter_order_Dir',$order);
		$search = $input->get('filter_str','');
		$filter = $input->get('filter','');
		if ($filter != '') {
			$w = explode('.',$filter);
			$filter_object_type=$w[0];
			$filter_object_id = $w[1];
		}
		// question1 or question2
		$query = $db->getQuery(true);
		$query->select('a.*, u.name, q.title');
		$query->from($this->dbTabla.' AS a');
		$query->leftjoin('#__users AS u ON u.id = a.user_id');
		$query->leftjoin('#__pvoks_questions AS q ON q.id = a.object_id');
		$query->where('(a.object_type="question1" or a.object_type="question2")');
		// Filter by search
		if (!empty($search)){
			$query->where('((u.name like "%'.$search.'%") or (q.title like "%'.$search.'%"))');
		}
		if ($filter != '') {
			$query->where('a.object_type like "'.$filter_object_type.'%"');
			$query->where('a.object_id = '.$db->quote($filter_object_id));
		}
		// option
		$query1 = $db->getQuery(true);
		$query1->select('a.*, u.name, opt.title');
		$query1->from($this->dbTabla.' AS a');
		$query1->leftjoin('#__users AS u ON u.id = a.user_id');
		$query1->leftjoin('#__pvoks_options AS opt ON opt.id = a.object_id');
		$query1->where('(a.object_type="option")');
		// Filter by search
		if (!empty($search)){
			$query1->where('((u.name like "%'.$search.'%") or (opt.title like "%'.$search.'%"))');
		}
		if ($filter != '') {
			$query1->where('a.object_type like "'.$filter_object_type.'%"');
			$query1->where('a.object_id = '.$db->quote($filter_object_id));
		}
		$query->union($query1);
		// category
		$query2 = $db->getQuery(true);
		$query2->select('a.*, u.name, c.title');
		$query2->from($this->dbTabla.' AS a');
		$query2->leftjoin('#__users AS u ON u.id = a.user_id');
		$query2->leftjoin('#__pvoks_categories AS c ON c.id = a.object_id');
		$query2->where('(a.object_type="category")');
		// Filter by search
		if (!empty($search)){
			$query2->where('((u.name like "%'.$search.'%") or (c.title like "%'.$search.'%"))');
		}
		if ($filter != '') {
			$query2->where('a.object_type like "'.$filter_object_type.'%"');
			$query2->where('a.object_id = '.$db->quote($filter_object_id));
		}
		$query->union($query2);
		// member
		$query3 = $db->getQuery(true);
		$query3->select('a.*, u.name, u2.name');
		$query3->from($this->dbTabla.' AS a');
		$query3->leftjoin('#__users AS u ON u.id = a.user_id');
		$query3->leftjoin('#__pvoks_members AS m ON m.id = a.object_id');
		$query3->leftjoin('#__users AS u2 ON u2.id = m.user_id');
		$query3->where('(a.object_type="member")');
		// Filter by search
		if (!empty($search)){
			$query3->where('((u.name like "%'.$search.'%") or (u2.mame like "%'.$search.'%"))');
		}
		if ($filter != '') {
			$query3->where('a.object_type like "'.$filter_object_type.'%"');
			$query3->where('a.object_id = '.$db->quote($filter_object_id));
		}
		$query->union($query3);
		// Add list oredring and list direction to SQL query
		$s = ''.$query.' ORDER BY '.$sort.' '.$order;
		//DBG echo $s;
		return $s;
	}
}
?>
