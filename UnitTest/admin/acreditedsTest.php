<?php
define('JPATH_COMPONENT', 'admin');
require_once "UnitTest/testJoomlaFramework.php";
require_once "admin/controllers/controller.php";
require_once "admin/helpers/pvoks.php";
require_once "admin/controllers/acrediteds.php";

class pvoksTestAcrediteds extends PHPUnit_Framework_TestCase {
	protected $controller;
	function __construct() {
		global $testData,$componentName,$viewName;
		$componentName = 'pvoks';
		$viewName = 'pvoks';
		$this->controller = new PvoksControllerAcrediteds();
		parent::__construct();
	}
    protected function setUp() {
		global $testData,$componentName;
		$testData->clear();
		$componentName = 'pvoks';
	}
	public function test_acrediteds_browse0()  {
		// nincs megjelenitendő rekord
		global $testData;
		$testData->clear();
		$this->controller->browse();
		$this->expectOutputRegex('/PVOKS_NO_DATA/');
	}	
	public function test_acrediteds_browse1()  {
		// van megjelenitendő rekord
		global $testData;
		$testData->clear();
		$testData->addDbResult(JSON_decode('[{"id":1,"title":"probaTitle1"},{"id":2,"title":"probaTitle2"}]'));
		$this->controller->browse();
		$this->expectOutputRegex('/probaTitle2/');
    }
	public function test_acrediteds_add()  {
		global $testData;
		$testData->clear();
		$this->controller->add();
		$this->expectOutputRegex('/BTN_SAVE/');
    }
	public function test_acrediteds_edit0()  {
		// nincs megadva rekord
		global $testData;
		$testData->clear();
		$this->controller->edit();
		$this->expectOutputRegex('/PVOKS_SELECT_PLEASE/');
	}	
	public function test_acrediteds_edit1()  {
		// meg van a rekord
		global $testData;
		$testData->clear();
		$testData->addDbResult(JSON_decode('{"id":1,"name":"probaTitle1"}'));
		$testData->setInput('id',1);
		$this->controller->edit();
		$this->expectOutputRegex('/BTN_SAVE/');
    }
    public function test_acrediteds_delete0()  {
		// nincs megadva rekord
		global $testData;
		$testData->clear();
		$this->controller->delete();
		$this->expectOutputRegex('/PVOKS_SELECT_PLEASE/');
	}	
    public function test_acrediteds_delete1()  {
		// meg van a rekord
		global $testData;
		$testData->clear();
		$testData->addDbResult(JSON_decode('{"id":1,"name":"probaTitle1"}'));
		$testData->setInput('id',1);
		$this->controller->delete();
		$this->expectOutputRegex('/DELETED/');
    }
    public function test_acrediteds_save0()  {
		// a formon nincsenek kitöltve az igényelt mezők
		global $testData;
		$testData->clear();
		$this->controller->save();
		$this->expectOutputRegex('/hibaUzenet/');
	}
    public function test_acrediteds_save1()  {
		// új felvitel és ez az adat már létezik az adatbázisban
		global $testData;
		$testData->clear();
		$testData->setInput('jform',array("id" => 0, "category_id" => 11,"acredited_id" => 12,"user_id" => 13));
		$testData->addDbResult(JSON_decode('{"id":12,"name":"acredited1"}')); // record exists check
		$testData->addDbResult(false);  // hurok check
		$testData->addDbResult(false);  // hurok check
		$testData->addDbResult(false);  // hurok check
		$testData->addDbResult(false);  // hurok check
		$this->controller->save();
		$this->expectOutputRegex('/EXISTS/');
	}
    public function test_acrediteds_save2()  {
		// új felvitel; hivatkozási hurok
		global $testData;
		$testData->clear();
		$testData->setInput('jform',array("id" => 0, "category_id" => 11,"acredited_id" => 12,"user_id" => 13));
		$testData->addDbResult(false); // rekord még nem létezik
		$testData->addDbResult(JSON_decode('{"id":11,"user_id":20,"category_id":1}')); // kategoria tag
		$testData->addDbResult(JSON_decode('{"id":12,"user_id":20,"acredited_id":21}')); // hurok ellenörzés start rekord
		$testData->addDbResult(JSON_decode('{"id":13,"user_id":21,"acredited_id":22,"name":"nev21"}')); // hurok check 21 -> 22
		$testData->addDbResult(JSON_decode('{"id":14,"user_id":22,"acredited_id":23,"name":"nev22"}')); // hurok check 22 -> 23
		$testData->addDbResult(JSON_decode('{"id":15,"user_id":23,"acredited_id":21,"name":"nev21"}')); // hurok check 23 -> 21
		$testData->addDbResult(JSON_decode('{"id":13,"user_id":21,"acredited_id":22,"name":"nev21"}')); // hurok check 21 -> 22  HUROK! 
		$this->controller->save();
		$this->expectOutputRegex('/PVOKS_ACREDITE_CIRCLE/');
	}
    public function test_acrediteds_save3()  {
		// új felvitel a formon ki vannak töltve az igényelt mezők, jó adatokkal
		global $testData;
		$testData->clear();
		$testData->setInput('jform',array("id" => 0, "category_id" => 11,"acredited_id" => 12,"user_id" => 13));
		$testData->addDbResult(false); // rekord még nem létezik
		$testData->addDbResult(JSON_decode('{"id":12,"user_id":21}')); // category member check
		$testData->addDbResult(JSON_decode('{"id":12,"user_id":20,"acredited_id":21}')); // hurok ellenörzés start rekord
		$testData->addDbResult(JSON_decode('{"id":13,"user_id":21,"acredited_id":22}')); // hurok check 21 -> 22
		$testData->addDbResult(JSON_decode('{"id":14,"user_id":22,"acredited_id":23}')); // hurok check 22 -> 23
		$testData->addDbResult(JSON_decode('{"id":15,"user_id":23,"acredited_id":0}')); // hurok check 23 -> 0   OK 
		$this->controller->save();
		$this->expectOutputRegex('/SAVED/');
	}	
    public function test_acrediteds_save4()  {
		// modosítás a formon ki vannak töltve az igényelt mezők, jó adatokkal
		global $testData;
		$testData->clear();
		$testData->setInput('jform',array("id" => 10, "category_id" => 11,"acredited_id" => 12,"user_id" => 13));
		$testData->addDbResult(false);        // modositás után nem lesz dupla kulcs
		$testData->addDbResult(JSON_decode('{"id":12,"user_id":21}')); // category member check
		$testData->addDbResult(JSON_decode('{"id":12,"user_id":20,"acredited_id":21}')); // hurok ellenörzés start rekord
		$testData->addDbResult(JSON_decode('{"id":13,"user_id":21,"acredited_id":22}')); // hurok check 21 -> 22
		$testData->addDbResult(JSON_decode('{"id":14,"user_id":22,"acredited_id":23}')); // hurok check 22 -> 23
		$testData->addDbResult(JSON_decode('{"id":15,"user_id":23,"acredited_id":0}')); // hurok check 23 -> 0   OK 
		$this->controller->save();
		$this->expectOutputRegex('/SAVED/');
    }
}	
?>