<?php
/**
* software documentation creator fot php language
* mist ron in PHUnit (in github/shyppable)
*    cd github-repo_root
*    phpunit tools/documentCreator.php
* Licence: GNU/GPL
* Author: Fogler Tibor  tibor.fogler@gmail.com  2017.02.13.
*/


class DocumentCreatorTest extends PHPUnit_Framework_TestCase {
	//+ ========== configuration ===================
	private $title = 'EDE-IDE Joomla_PDEngine_plugin';
	// dir of source files relaive path from github-repo_root
    //- ========== configuration ===================

    public $docPath = 'docs/';
	private $filePath = '';


	protected function processCommentBlock($lines, &$i) {
		$result = array();
		$line = trim(str_replace("\t",'',$lines[$i]));
		while ((substr($line,0,2) != '*/') & ($i < (count($lines) - 1))) {
			if (substr($line,0,3) == '/**') {
				if (trim(substr($line,3,200)) != '')
				  $result[] = trim(substr($line,3,200));
				$i++;
				$line = trim(str_replace("\t",'',$lines[$i]));
			}
			if (substr($line,0,1) == '*') {
				if (trim(substr($line,1,200)) != '') {
				  $result[] = trim(substr($line,1,200));
				}
				$i++;
				$line = trim(str_replace("\t",'',$lines[$i]));
			} else {
				$i++;
			}
		}
		return $result;
	}

	/**
	* adjust commentBlock (create link for  @param@return ClassName)
	* @param array $commentBlock lines
	* @param bool $inFunction
	* @return string
	*/
	protected function adjustCommentBlock($commentBlock, $inFunction) {
		$result = '';
		foreach ($commentBlock as $line) {
			  $line = str_replace('  ',' ',$line);
			  $line = str_replace('  ',' ',$line);
			  $w = explode(' ',$line);
			  if (($w[0] == '@param') | ($w[0] == '@params') | ($w[0] == '@return')) {
					$w[] = '';
					$w[] = '';
					$w[] = '';
					$w[] = '';
					$w[] = '';
					$w[] = '';
					$w[] = '';
				 // 0-@param|@return 1-type 2-name 3.....-comment
				if (Ucfirst($w[1]) == $w[1]) {
					if ($inFunction)
						$link = '../'.$w[1];
					else
						$link = $w[1];
					$line = $w[0].' ['.$w[1].']('.$link.') '.
					    $w[2].' '.$w[3].' '. $w[4].' '.$w[5].' '.$w[6].' '.$w[7];
				}
			  }
			  $result .= "\n\n".$line;
		}
		return $result;
	}

	protected function functionHead($lines, &$i) {
		$result = $lines[$i];
		while ((strpos($result,')') <= 0) & ($i < (count($lines) - 1))) {
			$i++;
			$result .= $lines[$i];
		}
		$result = str_replace('  ',' ',$result);
		$result = str_replace('  ',' ',$result);
		$result = str_replace('  ',' ',$result);
		return trim($result);
	}

	/**
	* process one source file
	* @param string full filepath
	* @return string html code
	*/
	function processFile($fileName) {
		$this->filePath = $fileName;
		$work = array();
		$classes = array();  // array of Class
		  // Class: {"name":str,"comments":str, "lines": str, "methods": array of Method}
		  // Method: {"name":str, "comments":str, "lines": str}
		$commentBlock = array();
		$methods = array();
		$className = '';
		$classComment = '';
		$methodName = '';
		$methodComment = '';
		$inClass = false;
		$inFunction = false;
		if (file_exists($fileName)) {
			$lines = file($fileName);
			for ($i = 0; $i < count($lines); $i++) {
				$line = trim(str_replace("\t",'',$lines[$i]));
				if (substr($line,0,3)=='/**') {
					  if (count($commentBlock) == 0)
					     $commentBlock = $this->processCommentBlock($lines, $i);
					  else
						 $work[] = $this->adjustCommentBlock($this->processCommentBlock($lines, $i),false);
				} else if (substr($line,0,5)=='class') {
					$line = str_replace('  ',' ',$line);
					$c = count($classes);
					if  ($inFunction) {
						if ($c > 0) {
						  $m = count($classes[$c-1]->methods);
						  if ($m > 0) {
						    $classes[$c-1]->methods[$m-1]->lines .= implode("\n\n",$work)."\n";
						    $this->saveMethod($className, $classes[$c-1]->methods[$m-1]);
						  }
						}
						$work = array();
						$inFunction = false;
					}
					if ($inClass) {
						if ($c > 0) {
						  $classes[$c-1]->lines .= implode("\n\n",$work);
						  $this->saveClass($className, $classes[$c-1]);
						}
						$work = array();
						$className = '';
						$inClass = false;
					}
					$inClass = true;
					$w = explode(' ',$line);
					$className = $w[1];
					$classes[$c] = new stdClass();
					$classes[$c]->name = $className;
					$classes[$c]->comments = $this->adjustCommentBlock($commentBlock,false);
					$commentBlock = array();

					$line = str_replace('{','',$line);
					$line = str_replace('  ',' ',$line);
					$line = str_replace('  ',' ',$line);
					$w = explode(' ',$line);
					$classes[$c]->lines = '##'.$w[0].' '.$w[1].' extends ['.$w[2].']('.$w[2].'.md)';
					$classes[$c]->methods = array();
					$work = array();
				} else if ((substr($line,0,15)=='public function') |
				           (substr($line,0,18)=='protected function') |
				           (substr($line,0,16)=='private function') |
				           (substr($line,0,8)=='function')) {
					$line = $this->functionHead($lines, $i);
					$c = count($classes);
					$m = count($classes[$c-1]->methods);
					if ($inFunction) {
						if ($c > 0) {
						  $classes[$c-1]->methods[$m-1]->lines .= implode("\n\n",$work)."\n";
						  if ($m > 0)
						    $this->saveMethod($className, $classes[$c-1]->methods[$m-1]);
						}
						$work = array();
						$inFunction = false;
					}
					$inFunction = true;
					$line = str_replace('(',' (',$line);
					$w = explode(' ',$line);
					if ($w[0] == 'function')
						$methodName = $w[1];
					else
					    $methodName = $w[2];
					if ($c > 0) {
					  $classes[$c-1]->methods[$m] = new stdClass();
					  $classes[$c-1]->methods[$m]->name = $methodName;
					  $classes[$c-1]->methods[$m]->comments = $this->adjustCommentBlock($commentBlock,true);
					  $classes[$c-1]->methods[$m]->lines = '##'.str_replace('{','',$line);
					}
					$commentBlock = array();
					$work = array();
				} else if ((substr($line,0,6) == 'public') |
				           (substr($line,0,9) == 'protected') |
				           (substr($line,0,6) == 'define')) {
					$line = str_replace('  ',' ',$line);
					$line = str_replace('  ',' ',$line);
					$w = explode(' ',$line);
					if (($w[0] == 'public') | ($w[0] == 'protected')) {
						$w[] = '';
						$w[] = '';
						$w[] = '';
						$w[] = '';
						$w[] = '';
						$w[] = '';
						$w[] = '';
						$w[] = '';
						$w[] = '';
						$w[] = '';
						// 0-public|protected 1-name 2-\\ 3-dataType 4...name,comment
						if (Ucfirst($w[3]) == $w[3]) {
							if ($inFunction)
								$link = '../'.$w[3];
							else
								$link = $w[3];
							if ($link != '')
							   $line = $w[0].' '.$w[1].' ['.$w[3].']('.$link.') '.
							   $w[4].' '.$w[5].' '.$w[6].' '.$w[7].' '.$w[8].' '.$w[9];
						}
					}
					$c = count($classes);
					if (($inFunction) & ($c > 0)) {
					  $m = count($classes[$c-1]->methods);
					  if ($m > 0) $classes[$c-1]->methods[$m-1]->lines .= "\n\n".$line;
					} else if (($inClass) & ($c > 0)) {
					  $classes[$c-1]->lines .= "\n\n".$line;
					} else
					  $work[] = $line;
				}
			} //for
			$m = 0;
			$c = count($classes);
			if ($c > 0)	$m = count($classes[$c-1]->methods);
			if (($inFunction) & ($c > 0) & ($m > 0)) {
				$classes[$c-1]->methods[$m-1]->lines .= implode("\n\n",$work)."\n";
				$work = array();
				$this->saveMethod($className, $classes[$c-1]->methods[$m-1]);
				$inFunction = false;
			}
			if (($inClass) & ($c > 0)) {
				$classes[$c-1]->lines .= implode("\n\n",$work)."\n";
				$this->saveClass($className, $classes[$c-1]);
				$className = '';
				$inClass = false;
			}
		} else {
		  echo  '<div class="error">File not found</div>
		  <p>'.$fileName.'</p>
		  ';
		}
		return true;
	}

	/**
	* process all php file in dir (exlude last \)
	* @param string dirPath
	* @return void
	*/
	public function	processDir($dir) {
		$dir .= '/';
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if (strpos($file,'.php') > 0)
					   $this->processFile($dir.$file);
				    if (($file != '.') & ($file != '..') & ($file != 'tmpl') & (is_dir($dir.$file)))
						$this->processDir($dir.$file);
				}
				closedir($dh);
			}
		}

	}

	private function saveMethod($className, $method) {
		if (!is_dir($this->docPath))
			mkdir($this->docPath);
		if (!is_dir($this->docPath.'items/'))
			mkdir($this->docPath.'items/');
		$fp = fopen($this->docPath.'items/'.$className.'_'.$method->name.'.md','w+');
		fwrite($fp,'#'.$this->title."\n");
		fwrite($fp,'software documentation'."\n\n");
		fwrite($fp,'**class:['.$className.'](../'.$className.'.md)**'."\n\n");
		fwrite($fp,$method->comments."\n\n");
		fwrite($fp,$method->lines."\n\n");
		fwrite($fp,'[source](../../../'.$this->filePath.')'."\n\n");
		fwrite($fp,'[sw.docs root](../)'."\n\n");
		fwrite($fp,'*created by documentCreator*'."\n\n");
		fclose($fp);
	}

	private function saveClass($className, $class) {
		if (!is_dir($this->docPath))
			mkdir($this->docPath);
		if (!is_dir($this->docPath.'items/'))
			mkdir($this->docPath.'items/');
		$fp = fopen($this->docPath.$className.'.md','w+');
		fwrite($fp,'#'.$this->title."\n");
		fwrite($fp,'software documentation'."\n\n");
		fwrite($fp,$class->comments."\n\n");
		fwrite($fp,$class->lines."\n\n");
		fwrite($fp,'**Methods**'."\n\n");
		foreach ($class->methods as $method) {
		  fwrite($fp,'['.$method->name.'](items/'.$className.'_'.$method->name.'.md)'."\n\n");
		}
		fwrite($fp,"\n\n".'[source](../../'.$this->filePath.')'."\n\n");
		fwrite($fp,'[sw.docs root](./)'."\n\n");
		fwrite($fp,'*created by documentCreator*'."\n\n");
		fclose($fp);
	}


	// main
	public function test_main() {
		$this->docPath = 'docs/site/';
		$this->processDir('site');
		$this->docPath = 'docs/admin/';
		$this->processDir('admin');

	}
}

?>
