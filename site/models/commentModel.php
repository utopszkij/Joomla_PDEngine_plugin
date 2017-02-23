<?php
/**
* comment data model of EDE_IDE Joomla_PDEngine_plugin
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*
* EZ MÉG CSAK CSONTVÁZ
*
*/
require_once (PATH_COMPONENT.'/models/model.php');

class PvoksModelComment extends PvoksModel {
	public $id; // integer ID
    public $objectType; // string GROUP | MEMBER | VOTE  
	public $object_id; // integer id of linked object
	public $body; // string comment body
    public $user_id; // id of creator user 
    public $created; // datetime

	function __construct($object, $comment_id=0) {
		parent::__construct('comment');
		if ($comment_id > 0) $this->load($comment_id);
	}
}
?>
