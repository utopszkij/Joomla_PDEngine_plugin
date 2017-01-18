<?php
/**
* PVOKS-SERVER interface
* ezt a file-t akkor kell pvoks_server.php névre átkopizni ha használunk külsõ pvoks_servert
*/
class PVOKS_SERVER {
	
	protected $local = false;
	protected $server_URL = '';
	protected $appKey = '';
		
	/**
	* save question + options (insert or update)
	* @param JRecord question record
	* @param array of JRecord options
	* @return void
	*/
	public function saveQuestion($question, $options) {
		
	}
	
	/**
	* delete question + options (insert or update)
	* @param JRecord question record
	* @return void
	*/
	public function deleteQuestion($question) {
		
	}
	
	/**
	* send a voks
	* @param JRecord question record
	* @param JUser user
	* @param array of JRecord pvoksServer
	* @return void
	*/
	public function saveVoks($question, $user, $vokses) {
		
	}
	
	/**
	* delete my voks
	* @param JRecord question record
	* @param JUser user
	* @return void
	*/
	public function deleteVoks($uestion, $user) {
		
	}
	
	/**
	* get user voksed?
	* @param JRecord question record
	* @param JUser user
	* @return boolean
	*/
	public function getUserVoksed($question, $user) {
		
	}
	
	/**
	* get saved voks count
	* @param JRecord question record
	* @return integer
	*/
	public function getVoksCount($question) {
		
	}
	
	/**
	* get voter count
	* @param JRecord question record
	* @return integer
	*/
	public function getVoterCount($question) {
		
	}
	
}
$pvoksServer = new PVOKS_SERVER();
?>