<?php
/**
* voks data model of EDE_IDE Joomla_PDEngine_plugin
* Licence: GNU/GPL
* Author: Fogler Tibor tibor.fogler@gmail.com
*
* EZ MÉG CSAK CSONTVÁZ
*
* FIGYELEM!  titkos szavazásnál a user_id nem tárolódik a voks táblában, hanem a voter táblában tárolódik.
* a voks táblában egy képzett "szavazó_id tárolódik" amiből a user_id nem visszafejthető 
* (még bf -el sem!)
*
*/
require_once (PATH_COMPONENT.'/models/model.php');

class PvoksModelVoks extends PvoksModel {
	public $id; // integer ID
	public $vote_id; // integer id of linked object
	public $prefList; // ["choice_id"=>position,...] preferent list
    public $voter_id; // id of voter
    public $votekey; // string id in IDE voks server
	public $ballot; // string ballot id in IDE server
    public $user_id; // integer user_id only in public vote	!
    public $created; // datetime

	function __construct($vote, $voks_id=0) {
		parent::construct('voks');
		if ($comment_id > 0) $this->load($voks_id);
	}
}
?>
