<?php
define('JPATH_COMPONENT', 'admin');
require_once "UnitTest/testJoomlaFramework.php";
require_once "admin/controllers/controller.php";
require_once "admin/helpers/pvoks.php";
require_once "admin/controllers/categories.php";

class pvoksTestCategories extends PHPUnit_Framework_TestCase {
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
	public function test_categories_browse()  {
		global $testData;
		$controller = new PvoksControllerCategories();
		$controller->browse();
		$this->expectOutputRegex('/PVOKS_CATEGORIES_LIST/');
		
    }
	public function test_categories_add()  {
		global $testData;
		$controller = new PvoksControllerCategories();
		$controller->add();
		$this->expectOutputRegex('/PVOKS_CATEGORIES_ADD/');
    }
	public function test_categories_edit()  {
		global $testData;
		$controller = new PvoksControllerCategories();
		$controller->edit();
		$this->expectOutputRegex('/PVOKS_CATEGORIES_LIST/');
    }
    public function test_categories_delete()  {
		global $testData;
		$controller = new PvoksControllerCategories();
		$controller->delete();
		$this->expectOutputRegex('/submitbutton/');
    }
    public function test_categories_save()  {
		global $testData;
		$controller = new PvoksControllerCategories();
		$controller->save();
		$this->expectOutputRegex('/submitbutton/');
    }
}	
?>