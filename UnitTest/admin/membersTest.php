<?php
define('JPATH_COMPONENT', 'admin');
require_once "UnitTest/testJoomlaFramework.php";
require_once "admin/controllers/controller.php";
require_once "admin/helpers/pvoks.php";
require_once "admin/controllers/members.php";

class pvoksTestMembers extends PHPUnit_Framework_TestCase {
	protected $controller;
	function __construct() {
		global $testData,$componentName,$viewName;
		$componentName = 'pvoks';
		$viewName = 'pvoks';
		parent::__construct();
		$this->controller = new PvoksControllerMembers();
	}
    protected function setUp() {
		global $testData,$componentName;
		$testData->clear();
		$componentName = 'pvoks';
	}
	public function test_categories_browse0()  {
		global $testData;
		// nincs megjelenitendő rekord
		$testData->clear();
		$this->controller->browse();
		$this->expectOutputRegex('/PVOKS_NO_DATA/');
	}
	public function test_categories_browse1()  {
		global $testData;
		// van megjelenitendő rekord
		$testData->clear();
		$testData->addDbResult(JSON_decode('[{"id":1,"name":"probaTitle1"},{"id":2,"name":"probaTitle2"}]'));
		$this->controller->browse();
		$this->expectOutputRegex('/probaTitle2/');
    }
	public function test_categories_add()  {
		global $testData;
		$testData->clear();
		$this->controller->add();
		$this->expectOutputRegex('/PVOKS_BTN_SAVE/');
    }
	public function test_categories_edit0()  {
		global $testData;
		// nincs kijelölt a rekord
		$testData->clear();
		$this->controller->edit();
		$this->expectOutputRegex('/PVOKS_SELECT_PLEASE/');
	}
	public function test_categories_edit1()  {
		global $testData;
		// meg van a rekord
		$testData->clear();
		$testData->addDbResult(JSON_decode('{"id":1,"name":"probaTitle1"}'));
		$testData->setInput('id',1);
		$this->controller->edit();
		$this->expectOutputRegex('/PVOKS_BTN_SAVE/');
    }
    public function test_categories_delete0()  {
		global $testData;
		// nincs kijelölt rekord
		$testData->clear();
		$this->controller->delete();
		$this->expectOutputRegex('/PVOKS_SELECT_PLEASE/');
    }		
    public function test_categories_delete1()  {
		global $testData;
		// meg van a rekord
		$testData->clear();
		$testData->addDbResult(JSON_decode('{"id":1,"name":"probaTitle1"}'));
		$testData->setInput('id',1);
		$this->controller->delete();
		$this->expectOutputRegex('/DELETED/');
	}
    public function test_categories_save0()  {
		global $testData;
		// új felvitel jó adatokkal
		$testData->clear();
		$testData->setInput('jform',array("id" => 0, "name" => "testTitle1", "category_id" => 1));
		$this->controller->save();
		$this->expectOutputRegex('/SAVED/');
	}
    public function test_categories_save1()  {
		global $testData;
		// modositás jó adatokkal
		$testData->clear();
		$testData->setInput('jform',array("id" => 1, "name" => "testTitle1", "category_id" => 1));
		$this->controller->save();
		$this->expectOutputRegex('/SAVED/');
    }
}	
?>