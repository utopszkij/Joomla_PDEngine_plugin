<?php
require_once "UnitTest/testJoomlaFramework.php";

class pvoksTest extends PHPUnit_Framework_TestCase {
	public $ada;
	
	function __construct() {
		global $testData,$componentName,$viewName;
		define('JPATH_COMPONENT', 'pvoks/site');
		$componentName = 'pvoks';
		$viewName = 'pvoks';
		parent::__construct();
	}
    protected function setUp() {
		global $testData,$componentName;
		$testData->clear();
		$componentName = 'Adalogin';
		$this->setupTestDataForCorrectCall();
		$this->ada = new AdaloginModelAda_obj($testData);
	}
    public function test_0()  {
		global $testData;
    }
}	
?>