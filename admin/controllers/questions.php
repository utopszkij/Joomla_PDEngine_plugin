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
require_once JPATH_COMPONENT.DS.'models'.DS.'model.php';

class PvoksControllerQuestions extends PvoksController {

    function __construct() {
		parent::__construct();
		$this->viewName = 'questions';
		$this->modelName = 'questions';
		$this->formName = 'questions';
		$this->defSort = 'a.id';        
		$this->defOrder = 'asc';        
		$this->defLimit = 20;           
		$this->defTask = 'browse';      
		$this->lngPre = 'PVOKS_';
	}
	
	/**
	* echo browser action buttons
	* @params none
	* @return void
	*/
	protected function browseButtons() {
		$result = parent::browseButtons();
		$result[] = array('copy',JText::_($this->lngPre.'BTN_COPY'),'btn-add','icon-copy');
		return $result;
		
	}
	
	/**
	* inti jform'fields for save and New function
	*/
	protected function editNewInit(&$jform) {
	  $jform['id'] = 0;	
	  $jform['title'] = '';	
	  $jform['alias'] = '';	
	  $jform['text'] = '';	
	}
	
	/**
	* Új question + options létrehozása meglévő másolásával
	* @Request array cid
	*/
	public function copy() {
		$input = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$cid = $input->get('cid', array(), 'array');
		if (count($cid) == 1) {
			$id = $cid[0];
			$db->setQuery('INSERT INTO #__pvoks_questions 
			(`id`,`category_id`, `question_type`, `title`, `alias`, `introtext`, `fulltext`, 
			`state`, `publicvote`, `accredite_enabled`, `target_category_id`, `termins`, 
			`optvalid`, `votevalid`, `created`, `created_by`, `modified`, `modified_by`
			)
			select 0, category_id,question_type,concat(title," ","'.JText::_('PVOKS_REPRINT').'"),
			alias,introtext,`fulltext`,0,publicvote,accredite_enabled,target_category_id, 
			termins,optvalid,votevalid, 
			"'.date('Y-m-d').'","'.$user->id.'","",0
			from #__pvoks_questions
			where id='.$db->quote($id));
			if ($db->query()) {
				$newid = $db->insertid();
				$db->setQuery('update #__pvoks_questions set alias=concat(alias,'.$newid.') where id='.$newid);
				$db->query();
				$db->setQuery('INSERT INTO #__pvoks_options 
				(`id`, `question_id`, `title`, `alias`, `introtext`, `fulltext`, 
				`state`, `ordering`, `created`, `created_by`, `modified`, `modified_by`
				)
				select 0,'.$newid.',title,concat(alias,'.$newid.'),introtext,`fulltext`, 
				state,ordering,
				"'.date('Y-m-d').'","'.$user->id.'","",0
				from #__pvoks_options 
				where question_id = '.$db->quote($id));
				if ($db->query() == false) {
					$this->setError($db->getError(),'error');
				}
			} else {
				echo '<div class="error">'.$db->getError().'</div>';
			}
			echo '<div class="uzenet">'.JText::_('PVOKS_SAVED').'</div>';
		} else {
			echo '<div class="error">'.JText::_('PVOKS_SELECT_PLEASE').'</div>';
		}
		$this->browse();
	}
	
	/**
	  * adat tárolás
	*/
	public function save() {
		$input = JFactory::getApplication()->input;
		$session = JFactory::getSession();
		if ($session->get('lastTask') == 'save') {
			// a user refrest nyomott a böngészőben save után!
			$this->browse('','');
			return;
		}
		$session->set('lastTask','save');	
		JSession::checkToken( 'post' ) or die( 'Invalid Token' );	
		$model = $this->getModel($this->modelName);

		$jform  = $input->get('jform', array(), 'array');
		$item = new stdClass();
		foreach ($jform as $fn => $fv) $item->$fn = $fv;

		if ($this->accessControl('save',$item) == false) die(JText::_('ACCES_VIOLATION'));
		$result = $model->save($item);  
		if ($result) {
			$this->setMessage(JText::_('PVOKS_QUESTION').' '.JText::_($this->lngPre.'SAVED'),'info');
			$this->setRedirect(JURI::base().'index.php?option=com_pvoks&view=options&filter_question_id='.$model->getDBO()->insertid());
			$this->redirect();
		} else {
			if ($item->id == 0) {
			  $this->add($model->getError(),'hibaUzenet');
			} else {
			  $input->set('id',$item->id);	
			  $this->edit($model->getError(),'hibaUzenet');
			}
		}
	}
	
}
?>
