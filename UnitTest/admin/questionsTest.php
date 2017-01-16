<?php
define('JPATH_COMPONENT', 'admin');
require_once "UnitTest/testJoomlaFramework.php";
require_once "admin/controllers/controller.php";
require_once "admin/helpers/pvoks.php";
require_once "admin/controllers/questions.php";

class pvoksTestQuestions extends PHPUnit_Framework_TestCase {
	function __construct() {
		global $testData,$componentName,$viewName;
		$componentName = 'pvoks';
		$viewName = 'pvoks';
		parent::__construct();
	}
    protected function setUp() {
		global $testData,$componentName;
		$testData->clear();
		$componentName = 'pvoks';
	}
	public function test_questions_browse()  {
		global $testData;
		$controller = new PvoksControllerQuestions();
		$controller->browse();
		$this->expectOutputRegex('/PVOKS_QUESTIONS_LIST/');
		
    }
	public function test_questions_add()  {
		global $testData;
		$controller = new PvoksControllerQuestions();
		$controller->add();
		$this->expectOutputRegex('/PVOKS_QUESTIONS_ADD/');
    }
	public function test_questions_edit()  {
		global $testData;
		$controller = new PvoksControllerQuestions();
		$controller->edit();
		$this->expectOutputRegex('/PVOKS_QUESTIONS_LIST/');
    }
    public function test_questions_delete()  {
		global $testData;
		$controller = new PvoksControllerQuestions();
		$controller->delete();
		$this->expectOutputRegex('/submitbutton/');
    }
    public function test_questions_save()  {
		global $testData;
		$controller = new PvoksControllerQuestions();
		$controller->save();
		$this->expectOutputRegex('/submitbutton/');
    }
}	
?>