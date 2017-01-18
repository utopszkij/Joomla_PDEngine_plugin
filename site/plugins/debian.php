<?php 
/* 
* plugin file a pvoks componentshez
* class pvoksPlugin
*   methods: processCategorySuggestions
*            processMemberSuggestions
*            processQuestionSuggestions
*            processOptionSuggestions
*            processVoteSuggestions
*/
class pvoksPlugin {
	protected $category_id;
	protected $question_id;
	function __construct($category_id, $uestion_id) {
		$this->category_id = $category_id;
		$this->question_id = $question_id;
	}
	public function processCategorySuggestions() {
		
	}
	public function processMemberSuggestions() {
		
	}
	public function processQuestionSuggestions() {
		
	}
	public function processOptionSuggestions() {
		
	}
	public function processVoteSuggestions() {
	
	}
}
?>