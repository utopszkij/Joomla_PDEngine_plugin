<?php
/**
* endorse data model of EDE_IDE Joomla_PDEngine_plugin
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*
* EZ MÉG CSAK CSONTVÁZ
*
*/
require_once (PATH_COMPONENT.'/models/model.php');

class PvoksModelEndorse extends PvoksModel {
	public $id; // integer ID
    public $objectType; // string GROUP | MEMBER | VOTE | CHOICE 
	public $object_id; // integer id of linked object
	public $action; // string action_code ACTIVATE | CLOSE | ARCHIVE | DELETE | EXLUDE 
    public $user_id; // id of creator user 
    public $created; // datetime

	function __construct($object, $endorse_id=0) {
		parent::__construct('endorse');
		if ($endorse_id > 0) $this->load($endorse_id);
	}
}
?>
