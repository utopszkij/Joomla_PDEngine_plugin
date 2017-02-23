<?php
/**
* user data model of EDE_IDE Joomla_PDEngine_plugin
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*
* EZ MÉG CSAK CSONTVÁZ
*
*/
require_once (PATH_COMPONENT.'/models/model.php');

class PvoksModelUser extends PvoksModel {
	public $id;
	public $username;
    public $name;
    public $email;
    public $state;
    public $activated;
    public $groups;

	function __construct($parent, $user_id=0) {
		parent::construct('user');
		if ($user_id > 0) $this->load($user_id);
	}

}
?>
