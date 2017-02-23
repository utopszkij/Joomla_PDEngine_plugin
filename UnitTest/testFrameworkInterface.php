<?php
/**
* Framework Interface a likvid demokrácia szoftverhez
* unittest változat
*
* Licensz: GNU/GPL
* szerző: Fogler Tibor  tibor.fogler@gmail.com_addref
*
*/

/**
* must define in caller:
* define ('PATH_SITE','site'); must define in caller!
* define ('PATH_ADMINISTRATOR','site'); must define in caller!
* define ('PATH_COMPONENT','site'); must define in caller!
*/
define ('PATH_TEMPLATE', PATH_COMPONENT.'../templates');
define ('PATH_LANGUAGE', PATH_COMPONENT.'../language');
define ('PATH_DATA', PATH_COMPONENT.'/data');

global $secret; // string site secret Key
global $language; // string site language code example: 'hu-HU'
global $uri;  // string site root URI
global $user; // URI legged user
global $session; // Session  session object
global $input;   // Input input ($_GET|$_POST) supporter object
global $testData; // array_of_object test dataBase
global $testInput; // array_of_string test inputs
global $testSession; // array_of_object test session datas
global $templateName;  // string template name
global $testRemoteResult;  // array of string test remote results
global $testRemoteURI; // array_of_string mok remote call datas
$secret = '123456';
$language = 'hu-HU';
$uri = 'http://localhost';
$user = new User();
$session = new Session();
$input = new Input();
$testData = array();
$testInput = array();
$testSession = array();
$templateName = '';
$testRemoteURI = array();
$testRemoteResult = array();

/**
* compare function for usort $r1->sort is sortField
* @param resord
* @param record
* @return int  -1|0|+1
*/
function compFun($r1,$r2) {
	$f = $r1->sortField;
	if ($r1->$f < $r2->$f)
		$result = -1;
	else if ($r1->$f > $r2->$f)
		$result = +1;
	else
		$result = 0;
	return $result;
}

/**
* Framework Interface a likvid demokrácia szoftverhez
* unittest változat
*/
class FrameworkInterface {
  protected $secret; // string site secret key
  protected $templateName; // string template name
  protected $language; // string language code example: 'hu-HU'
  protected $uri; // string site root URI
  protected $model; // Model data model object
  protected $view; // View viewer object
  protected $helper; // Helper helper object
  protected $translate; // array_of_string translate strings key is token

  public $defTask; // string default task
  public $dataStorage; // DataStorage data stroge object
  public $ds; // DataStorage sort name of this->dataStorage
  public $input;  // Input input object
  public $session; // Session session object
  public $user; // User logged user object

  /**
  * constructor
  * @param string defTask
  * @return FrameworkInterface
  */
  function __construct($defTask='list')  {
	global $secret, $language, $uri, $user, $session, $input,  $testData, $testInput, $testSession, $templateName;
    $this->secret = &$secret;
    $this->$language = $language;
    $this->defTask = $defTask;
    $this->input = &$input;
    $this->session = &$session;
    $this->uri = $uri;
    $this->user = &$user;
    $this->dataStorage = new DataStorage();
		$this->ds = &$this->dataStorage;
    $this->translate = array();
		$this->templateName = $templateName;
    $this->defTask = $defTask;
  }

  function __destruct() {

	}

  /**
  * call remote process
  * @param string URI
  * @param string method 'post' | 'get'
  * @param array data ["name" => value,....]
  * @param string extraHeader
  * @return string
  */
  public function remoteCall($url,$method,$data,$extraHeader='') {
		$result = '';
		/*
		if ($extraHeader != '') {
			$extraHeader .= "\r\n";
		}
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n".$extraHeader,
				'method'=> $method,
				'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		return file_get_contents($url, false, $context);
		*/
		global $testRemoteURI, $testRemoteResult;
		$testRemoteURI[] = $url.' otions='.JSON_encode($options);
		if (count($testRemoteResult) > 0) {
		  $result = $testRemoteResult[0];
		  array_splice($testRemoteResult, 0, 1);
		}
		return $result;
  }

  /**
  * load language file into $this->translate
  * @param string componentName
  * @param string variantName
  * @return void
  */
  public function loadLanguage($componentName, $variantName='') {
	  $lines = array();
	  $line = '';
	  $w = array();
	  $this->translate = array();
	  $fileName = JPATH_LANGUAGE.'/'.$this->$language.'.'.$componentName.'.ini';
	  if (file_exists($fileName)) {
		  $lines = file($fileName);
		  foreach ($lines as $line) {
			  $w = exlode('=',$line);
			  $this->translate[$w[0]] = str_replace('"','',$w[1]);
		  }
	  }
	  if ($variantName != '') {
		  $fileName = JPATH_LANGUAGE.'/'.$this->$language.'.'.$componentName.'.'.$variantName.'.ini';
		  if (file_exists($fileName)) {
			  $lines .= file($fileName);
			  foreach ($lines as $line) {
				  $w = exlode('=',$line);
				  $this->translate[$w[0]] = str_replace('"','',$w[1]);
			  }
		  }
	  }
  }

  /**
  * tranlate language token
  * @param string token
  * @return string
  */
  public function _($token) {
	  if (isset($this->translate[$token]))
		  $result = $this->translate[$token];
	  else
		  $result = $token;
	  return $result;
  }

  /**
  * do login process check state, activation, psw
  * @param string username
  * @param string password
  * @return bool if true set global user object
  */
  public function login($username, $psw) {
		$result = false;
    $ds = new DataStorage();
		$items = $ds->getItems('users',' r.username = "'.$username.'"');
		if (count($items) == 1) {
			if (($items[0]->psw == md5($psw)) &
			    ($items[0]->state == 1) &
			    ($items[0]->activated == 1)) {
				$this->user->id = $items[0]->id;
				$this->user->username = $items[0]->username;
				$this->user->name = $items[0]->name;
				$this->user->email = $items[0]->email;
				$this->user->psw = $items[0]->psw;
				$this->user->groups = $items[0]->groups;
				$result = true;
			} else {
				$result = false;
			}
		} else {
			$result = false;
		}
		return $result;
  }

  /**
  * do logout process
  * init global user object
  * @return void
  */
  public function logout() {
		$this->user->id = 0;
		$this->user->username = '';
		$this->user->name = '';
		$this->user->groups = array();
		return true;
  }

	/**
	* send email
	* @param string $to email
	* @param string $Subject
	* @param string $body
	* @return bool
	*/
	public function mailto($to,$subject,$body) {
		global $testData;
		$testData['mails'][] = 'to:'.$to."\n".'subject:'.$subject."\n".$body;
		return true;
	}

} // FrameWorkInterface

/**
* user object
*/
class User {
	public $id = 0;  // integer identifyer code
	public $username = ''; // string nick name
	public $name = ''; // strig valid name
	public $email = ''; // string email
	public $psw = ''; // string password in md5 hash
	public $groups = array(); // array_of_string usergroups
	public $state = 1; // integer state 1-active, 0-disabled
	public $activated = 1; // integer 1-activated, 0-not activated

	/**
	* save user properties into database
	* @return bool
	*/
  public function save() {
		$result = false;
		$ds = new DataStorage();
		$result = $ds->save('users',$this);
		return $result;
	}

	/**
	* create a new user object into database
	* @param string username
	* @param string name
  * @param string email
  * @param integer state
  * @return bool
	*/
  public function regist($username, $name, $email, $state) {
		$result = fasle;
		$ds = new DataStorage();
		$rec = new stdClass();
		$rec->username = $username;
		$rec->name = $name;
		$rec->email = $email;
        $rec->state = $state;
        $rec->activated = 1;
        $rec->groups['registered'] = true;
        $return = $ds->save($rec);
	}
} // User

/**
* data storage object
*/
class DataStorage {
  protected $msg = '';	// error Massage
  public $databaseName = '';

  /**
  * load testdata from file
  * @param string databaseName
  * @return void
  */
  public function open($databaseName) {
	  global $testData;
	  $this->databaseName = $databaseName;
	  $testData = array();
	  if (file_exists(JPATH_DATA.'/'.$databaseName.'.dat')) {
		  $lines = file(JPATH_SITE.'/data/'.$databaseName.'.dat');
		  $testdate = JSON_decode(implode("\n",$line));
	  }
  }

  /*
  * save testdata into file
  * @return void
  */
  public function close() {
	  global $testData;
	  $fp = fopen(JPATH_DATA.'/'.$this->databaseName,'w+');
	  fwrite($fp, JSON_encode($testData));
	  fclose($fp);

  }

  /**
  * convert sql syntax to php syntax
  * @param string sql expression FIGYELEM! ' r.' table.alias
	*           SZOKÖZ KÖTELEŐ ELEM!, ' = ',' and ',' or ',' <> ' szoközök kötelező elemek!
  * @return string
  */
  private function adjustFilter($filter) {
		$result = str_replace(' r.','$r->',$filter);
		$result = str_replace(' = ',' == ',$result);
		$result = str_replace(' and ',' & ',$result);
		$result = str_replace(' or ',' | ',$result);
		$result = str_replace(' <> ',' != ',$result);
		return $result;
  }

  /**
  * get object from $testData by tableName and id
  * @param string tableName
  * @param integer id
  * @return record|false  if false set this->msg
  */
  public function getItem($tableName, $id) {
		global $testData;
	  if (isset($testData['tableName'][$id])) {
		  $result = $testData['tableName'][$id];
	  } else {
		  $result = false;
		  $this->msg = 'Not found';
	  }
  }

  /**
  * get items array from $testdata by tablename and filter
  * @param string tableName
  * @param string use sql syntax ' r.' alias,  ' = '
  * @param string ordering sql syntax (test use only first fieldname)
  * @param int limit
  * @param int limitStart
  * @return arry_of_record
  */
  public function getItems($tableName, $filter, $order='', $limit=0, $limitStart=0) {
	  global $testData;
	  $result = array();
	  $wresult = array();
	  $filter = $this->adjustFilter($filter);

	  // test use only first fieldname in $order
	  $w = explode(',',$order);
	  $w = explode(' ',$w[0]);
	  $oreder = $w[0];

	  $i = 0;
	  $j = 0;
	  $w = false;
	  if (isset($testData[$tableName])) {
		if (is_array($testData[$tableName])) {
			  if ($order != '') {
				  foreach ($testData[$tableName] as $r) {
						eval('$w =('.$filter.');');
						if ($w) {
							$r->sortField = $order;
							$wresult[] = $r;
						}
				  }
				  usort($wresult,"compFun");
				  foreach ($wresult as $r) {
					  if (($i >= $limitStart) & (($j <= $limit) | ($limit == 0))) {
							unset($r->sortField);
							$result[] = $r;
					  }
					  $i++;
					  $j++;
				  }
				  unset($wresult);
			  } else {
				  foreach ($testData[$tableName] as $r) {
						eval('$w =('.$filter.');');
						if ($w) {
						   if (($i >= $limitStart) & (($j <= $limit) | ($limit == 0))) {
							 $result[] = $r;
						   }
						}
						$i++;
						$j++;
				  } // foreach
			  } // $order != ''
		} // is_array($testData[$tableName])
	  } // isset($testData[$tableName])
	 return $result;
  }

  /**
  * get id array from $testdata by tablename and filter
  * @param string tableName
  * @param string use sql syntax ' r.' alias,  ' = '
  * @return array_of_integer
  */
  public function getIds($tableName, $filter) {
	  global $testData;
	  $result = array();
	  $filter = $this->adjustFilter($filter);
	  if (isset($testData[$tableName])) {
		  if (is_array($testData[$tableName])) {
			  foreach ($testData[$tableName] as $r) {
				  eval('$w =('.$filter.');');
				  if ($w) {
					  $result[] = $r->id;
				  }
			  }
		  }
	  }
	  return $result;
  }

  /**
  * get record count from $testdata by tablename and filter
  * @param string tableName
  * @param string filter use sql syntax ' r.' alias,  ' = '
  * @return integer
  */
  public function getCount($tableName, $filter) {
	  global $testData;
	  $result = 0;
	  $filter = $this->adjustFilter($filter);
	  if (isset($testData[$tableName])) {
		  if (is_array($testData[$tableName])) {
			  foreach ($testData[$tableName] as $r) {
				  eval('$w =('.$filter.');');
				  if ($w) {
					  $result++;
				  }
			  }
		  }
	  }
	  return $result;

  }

  /**
  * add new object into $testData
  * @param string tableName
  * @param record result: set new id into record
  * @return bool if false set this->msg
  */
  public function add($tableName, &$recordObject) {
	  global $testData;
	  $result = false;
	  if ($recordObject->id == 0) {
		$w = $this->getIds($tableName,'$r->id > 0');
		if (count($w) > 0)
			$newId = $w[count($w)-1] + 1;
		else
			$newId = 1;
		$recordObject->id = $newId;
	  } else {
		  $newId = $recordObject->id;
	  }
      $testdata[$tableName][$newid] = $recordObject;
	  return true;
  }

  /**
  * add new object into $testData
  * @param string tableName
  * @param string record in json format
  * @return bool if false set this->msg
  */
  public function  addJson($tableName, $jsonStr) {
	  global $testData;
	  $result = false;
	  $recordObject = JSON_decode($jsonStr);
	  if ($recordObject->id == 0) {
		  $w = $this->getIds($tableName,'$r->id > 0');
		  if (count($w) > 0)
			$newId = $w[count($w)-1] + 1;
		  else
			$newId = 1;
		  $recordObject->id = $newId;
	  } else {
		  $newId = $recordObject->id;
	  }
      $testData[$tableName][$newId] = $recordObject;
	  return true;
  }

  /**
  * edit records by filter
  * @param string tableName
  * @param record update fields and values
  * @param string filter use sql syntax ' r.' alias,  ' = '
  * @return bool if false set this->msg
  */
  public function edit($tableName, $recordObject, $filter) {
	  global $testData;
	  $result = false;
	  $filter = $this->adjustFilter($filter);
	  $w = $this->getIds($tableName,$filter);
	  if (count($w) > 0) {
		  foreach ($w as $id) {
			  $rec = $this->getItem($tableName, $id);
			  foreach ($recordObject as $fn => $fv)
			    $rec->$fn = $fv;
			  $testdata[$tableName][$id] = $rec;
		  }
	  }
	  return true;
  }

  /**
  * delete records by filter
  * @param string tableName
  * @param string filter use sql syntax ' r.' alias,  ' = '
  * @return bool if false set this->msg
  */
  public function delete($tableName, $filter) {
	  global $testData;
	  $result = false;
	  $filter = $this->adjustFilter($filter);
	  $w = $this->getIds($tableName,$filter);
	  if (count($w) > 0) {
		  foreach ($w as $id) {
			  unset($testdata[$tableName][$id]);
		  }
	  }
	  return true;
  }

  /**
  * get this->msg
  * @return string
  */
  public function getErrorMsg() {
	  return $this->msg;
  }
} // DataStorage

/**
* Input object
*/
class Input {
  /**
  * get data from GET or POST use filter
  * @param string name
  * @param string default value
  * @param string 'string'|'html'
  * @return string
  */
  public function get($name, $default='', $tipus='string') {
		global $testInput;
		if (isset($testInput[$name]))
			$result =  $testInput[$name];
		else
			$result = $default;
		return $result;
	}

	/**
  * set data into global input varable
  * @param string name
  * @param string value
  * @return void
  */
  public function set($name,$value) {
	  global $testInput;
	  $testInput[$name] = $value;
	  return true;
  }
} // Input

/**
* session object
*/
class Session {

  /**
  * get data from session
  * @param string name
  * @return string
  */
  public function get($name, $default='') {
		global $testSession;
		if (isset($testSession[$name]))
			$result =  $testSession[$name];
		else
			$result = $default;
	  return $result;
  }

  /**
  * set data into session
  * @param string name
  * @param string value
  * @return void
  */
  public function set($name,$value) {
	  global $testSession;
	  $testSession[$name] = $value;
	  return true;
  }

  /**
  * create new CSR token, save it into session
  * @return string
  */
  public function getFormToken() {
	  $this->set('formtoken', md5(rand(100000,999999)));
	  return  $this->get('formtoken');
  }

  /**
  * check CSR token
  * @param string
  * @return bool
  */
  public function checkFormToken($token) {
	  return ($token == $this->get('formtoken'));
  }
} // Session

?>
