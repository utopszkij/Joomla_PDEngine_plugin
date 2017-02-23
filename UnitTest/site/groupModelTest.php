<?php
if (!defined('PATH_COMPONENT'))
	define ('PATH_COMPONENT','site/');
if (!defined('PATH_SITE'))
	define ('PATH_SITE','site/');
if (!defined('PATH_ADMINISTRATOR'))
	define ('PATH_ADMINISTRATOR','admin/');
require_once "UnitTest/testFrameworkInterface.php";
require_once "site/models/groupModel.php";

class modelGroupTest extends PHPUnit_Framework_TestCase {
	protected $fw;
	function __construct() {
		parent::__construct();
		$this->fw  = new FrameworkInterface();
	}
  protected function setUp() {
	  	global $testData;
      $this->fw->dataStorage->addJson('users',
	    '{"id":1,"username":"testelek","psw":"","name":"Test Elek","email":"test@test.hu","state":1,"activated":1,"groups":[]}');
	  	$testData['users'][1]->psw = md5('123456');
	}
	public function test_load_notfound() {
			$model = new PvoksModelGroup(null);
			$res = $model->load(12);
			$this->assertEquals(false, $res);
			// $this->expectOutputRegex('/testJoomlaFramwork view\.display regist/');
	}
	public function test_new_store() {
			$model = new PvoksModelGroup(null);
			$res = $model->newRecord();
			$res = $model->store();
			$this->assertEquals(true, $res);
			// $this->expectOutputRegex('/testJoomlaFramwork view\.display regist/');
	}


}
?>
