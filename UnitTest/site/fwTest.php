<?php
if (!defined('PATH_COMPONENT'))
	define ('PATH_COMPONENT','site/');
if (!defined('PATH_SITE'))
	define ('PATH_SITE','site/');
if (!defined('PATH_ADMINISTRATOR'))
	define ('PATH_ADMINISTRATOR','admin/');
require_once "UnitTest/testFrameworkInterface.php";

class userloginTest extends PHPUnit_Framework_TestCase {
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
  public function test_loginOK()  {
				$res = $this->fw->login('testelek','123456');
        $this->assertEquals(true, $res);
		// $this->expectOutputRegex('/testJoomlaFramwork view\.display regist/');
	}
  public function test_login_wrongPsw()  {
		$res = $this->fw->login('testelek','hibas');
		$this->assertEquals(false, $res);
	}
  public function test_login_wrongUsername()  {
		$res = $this->fw->login('hibas','123456');
    $this->assertEquals(false, $res);
	}
  public function test_login_wrongState()  {
		global $testData;
		$testData['users'][1]->state = 0;
		$res = $this->fw->login('testelek','123456');
        $this->assertEquals(false, $res);
	}
  public function test_login_notActivated()  {
		global $testData;
		$testData['users'][1]->state = 1;
		$testData['users'][1]->activated = 0;
		$res = $this->fw->login('testelek','123456');
        $this->assertEquals(false, $res);
	}
  public function test_logout()  {
		$res = $this->fw->logout('');
        $this->assertEquals(true, $res);
	}
}
?>
