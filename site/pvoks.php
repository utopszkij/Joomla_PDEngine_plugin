<?php
/**
  * szavazok component
  *   taskok: szavazok, szavazatEdit, szavazatDelete, eredmeny, szavazatSave
  * Licensz: GNU/GPL
  * Szerző: Fogler Tibor   tibor.fogler@gmail.com_addref
  * web: github.com/utopszkij/elovalasztok2018
  * Verzió: V1.00  2016.09.14.
  *
  */

  // SEO URL kezelés
  //  URI::root().'component/pvoks/categoryAlias/questionAlias][?par=value[&par=value]...]
  include_once JPATH_SITE.'/components/com_pvoks/controller.php';
  include_once JPATH_SITE.'/components/com_pvoks/funkciok.php';
  $input = JFactory::getApplication()->input;  
  $db = JFactory::getDBO();
  $w = explode('/',$_SERVER['REQUEST_URI']);
  if (count($w) > 3) {
	  foreach ($w as $i => $w1) {
		if ($w1 == 'component') {
			if ($i < (count($w) - 2)) {
				if ($w[$i+1] == 'pvoks') {
					$categoryAlias = $w[$i+2];
					$questionAlias = $w[$i+3];
					$db->setQuery('select id from #__pvoks_categories where alias='.$db->quote($categoryAlias));
					$res = $db->loadObject();
					if ($res)
					  $input->set('category',$res->id);
					$db->setQuery('select id from #__pvoks_questions where alias='.$db->quote($categoryAlias));
					$res = $db->loadObject();
					if ($res)
					  $input->set('question',$res->id);
				}
			}
		}  
	  }
  }
  $task = $input->get('task','categories.browse');
  if (strpos($task,'.') > 0) {
	  $w = explose('.',$task);
	  $input->set('view',$w[0]);
	  $input->set('task',$w[1]);
	  $task = $w[1];
  }
  
  echo $input->get('category').' / '.$input->get('question').' task='.$input->get('task').'<br />';

  $controller = new pvoksController();
  $controller->$task ();
?>
