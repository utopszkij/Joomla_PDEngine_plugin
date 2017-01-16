<?php
define('JPATH_COMPONENT', 'admin');
require_once "UnitTest/testJoomlaFramework.php";
require_once "admin/controllers/controller.php";
require_once "admin/helpers/pvoks.php";
require_once "admin/controllers/members.php";

class pvoksTestMembers extends PHPUnit_Framework_TestCase {
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
	public function test_members_browse()  {
		global $testData;
		$controller = new PvoksControllerMembers();
		$controller->browse();
		$this->expectOutputRegex('/PVOKS_MEMBERS_LIST/');
		
    }
	public function test_members_add()  {
		global $testData;
		$controller = new PvoksControllerMembers();
		$controller->add();
		$this->expectOutputRegex('/PVOKS_MEMBERS_ADD/');
    }
	public function test_members_edit()  {
		global $testData;
		$controller = new PvoksControllerMembers();
		$controller->edit();
		$this->expectOutputRegex('/PVOKS_MEMBERS_LIST/');
    }
    public function test_members_delete()  {
		global $testData;
		$controller = new PvoksControllerMembers();
		$controller->delete();
		$this->expectOutputRegex('/submitbutton/');
    }
    public function test_members_save()  {
		global $testData;
		$controller = new PvoksControllerMembers();
		$controller->save();
		$this->expectOutputRegex('/submitbutton/');
    }
}	
?>