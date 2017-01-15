<?php
define('JPATH_COMPONENT', 'admin');
require_once "UnitTest/testJoomlaFramework.php";
require_once "admin/controllers/controller.php";
require_once "admin/helpers/pvoks.php";
require_once "admin/controllers/configs.php";

class pvoksTest extends PHPUnit_Framework_TestCase {
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
	public function test_configs_browse()  {
		global $testData;
		$controller = new PvoksControllerConfigs();
		$controller->browse();
		$this->expectOutputRegex('/PVOKS_CONFIGS_LIST/');
		
    }
	public function test_configs_add()  {
		global $testData;
		$controller = new PvoksControllerConfigs();
		$controller->add();
		$this->expectOutputRegex('/PVOKS_CONFIGS_ADD/');
    }
	public function test_configs_edit()  {
		global $testData;
		$controller = new PvoksControllerConfigs();
		$controller->edit();
		$this->expectOutputRegex('/PVOKS_CONFIGS_LIST/');
    }
    public function test_configs_delete()  {
		global $testData;
		$controller = new PvoksControllerConfigs();
		$controller->delete();
		$this->expectOutputRegex('/submitbutton/');
    }
    public function test_configs_save()  {
		global $testData;
		$controller = new PvoksControllerConfigs();
		$controller->save();
		$this->expectOutputRegex('/submitbutton/');
    }
}	
?>