<?php
define('JPATH_COMPONENT', 'admin');
require_once "UnitTest/testJoomlaFramework.php";
require_once "admin/controllers/controller.php";
require_once "admin/helpers/pvoks.php";
require_once "admin/controllers/options.php";

class pvoksTestOptions extends PHPUnit_Framework_TestCase {
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
	public function test_options_browse()  {
		global $testData;
		$controller = new PvoksControllerOptions();
		$controller->browse();
		$this->expectOutputRegex('/PVOKS_OPTIONS_LIST/');
		
    }
	public function test_options_add()  {
		global $testData;
		$controller = new PvoksControllerOptions();
		$controller->add();
		$this->expectOutputRegex('/PVOKS_OPTIONS_ADD/');
    }
	public function test_options_edit()  {
		global $testData;
		$controller = new PvoksControllerOptions();
		$controller->edit();
		$this->expectOutputRegex('/PVOKS_OPTIONS_LIST/');
    }
    public function test_options_delete()  {
		global $testData;
		$controller = new PvoksControllerOptions();
		$controller->delete();
		$this->expectOutputRegex('/submitbutton/');
    }
    public function test_options_save()  {
		global $testData;
		$controller = new PvoksControllerOptions();
		$controller->save();
		$this->expectOutputRegex('/submitbutton/');
    }
}	
?>