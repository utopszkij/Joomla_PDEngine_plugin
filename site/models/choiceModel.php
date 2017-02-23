<?php
/**
* choice data model of EDE_IDE Joomla_PDEngine_plugin
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*
* EZ MÉG CSAK CSONTVÁZ
*
*/
require_once (PATH_COMPONENT.'/models/model.php');

class PvoksModelChoicet extends PvoksModel {
	public $id; // integer ID
	public $vote_id; // integer id of linked object
	public $state; // integer status PROPOSED | ACTIVE
	public $title; // string short title
	public $desc; // text description
    public $user_id; // id of creator user 
    public $created; // datetime

	function __construct($vote, $choice_id=0) {
		parent::construct('choice');
		if ($comment_id > 0) $this->load($choice_id);
	}
}
?>
