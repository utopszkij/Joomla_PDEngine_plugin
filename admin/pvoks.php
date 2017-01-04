<?php
/**
  * com_pvoks
  * Licence: GNU/GPL
  * Author: Fogler Tibor   
  * Author-email: tibor.fogler@gmail.com
  * Author-web: github.com/utopszkij
  * Verzió: V1.00 
  */
  include_once JPATH_ADMINISTRATOR.'/components/com_pvoks/controllers/controller.php';
  include_once JPATH_ADMINISTRATOR.'/components/com_pvoks/helpers/pvoks.php';
  $pvoksHelper = new PvoksHelper();
  $document = JFactory::getDocument();
  $input = JFactory::getApplication()->input;  
  $task = strtolower($input->get('task','browse'));
  $view = strtolower($input->get('view','configs'));
  if ($task == '') $task = 'browse';
  if ($view == '') $view = 'configs';
  if (strpos($task,'.')) {
	  $i = strpos($task,'.');
	  $view = substr($task,0,$i);
	  $task = substr($task,$i+1,100);
  }
  if (file_exists(JPATH_ADMINISTRATOR.'/components/com_pvoks/controllers/'.$view.'.php')) {
	include_once  JPATH_ADMINISTRATOR.'/components/com_pvoks/controllers/'.$view.'.php';
	$controllerName = 'PvoksController'.ucfirst($view);
    $controller = new $controllerName ();
  } else {
    $controller = new PvoksController();
  }	
  $pvoksHelper->echoPvoksHeader();
  if (file_exists(JPATH_COMPONENT.'/assets/common.js'))	
       $document->addScript(JURI::base().'components/com_pvoks/assets/common.js');	
  $controller->set('pvoksHelper',$pvoksHelper);
  $controller->setViewName($view);
  $controller->$task ();
?>
