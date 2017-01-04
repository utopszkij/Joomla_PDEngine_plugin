<?php
 /**
  * szavazok model
  * taskok: szavazok, szavazatEdit, szavazatDelete, eredmeny, szavazatSave
  * Licensz: GNU/GPL
  * Szerzõ: Fogler Tibor   tibor.fogler@gmail.com_addref
  * web: github.com/utopszkij/elovalasztok2018
  * Verzió: V1.00  2016.09.14.
  * Adat tárolás:   szavazás: #__category (params a metadata mezõben JSON formában) MINIMUM:{"status":"vita1"}
  *                 alternativák #__content
  *
  */

include_once JPATH_SITE.'/components/com_pvoks/accescontrol.php';  
include_once JPATH_SITE.'/components/com_pvoks/funkciok.php';  
  
class pvoksModelPvoks extends JModelList {
	private $errorMsg = '';
	
	/** { 
	*		"status":"vita1",  								("vita0","vita1","vita2","szavazas","lezart"....)
	*       "steps":["vita0":["usergr1":["akcio1","akcio2"...],"usergr2:["akcio1",'akcio2"....]...],
	*                 ["vita1":["usergr1":["akcio1","akcio2"...],"usergr2:["akcio1",'akcio2"....]...]...
	*       "auto":{"feltétel":"algoritmus" ...}
	*       ]
	*	}
	*/	
	private $defSzavazasParams;
	
	function __construct() {
		parent::__construct();
		
		$this->defSzavazasParams = JSON_encode('{
			"steps":["vita0:":["guest":["view-suggestion","view-alternative","view-creator","view-comment","comment"],
			                   "registered":["power"],
							   "admin":["setStep"]],
			         "vita1":["guest":["view-suggestion","view-alternative","view-creator","view-comment","comment"],
			                   "registered":["comment"]],
							   
			]
		}');
		
		$db = JFactory::getDBO();
		$db->setQuery('CREATE TABLE IF NOT EXISTS #__szavazatok (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `temakor_id` int(11) NOT NULL COMMENT "témakör azonosító",
		  `szavazas_id` int(11) NOT NULL COMMENT "szavazás azonosító",
		  `szavazo_id` int(11) NOT NULL COMMENT "szavaó azonosító a concorde-shulze kiértékeléshez",
		  `user_id` int(11) NOT NULL COMMENT "Ha nyilt szavazás a szavazó user_id -je",
		  `alternativa_id` int(11) NOT NULL COMMENT "alternativa azonositó",
		  `pozicio` int(11) NOT NULL COMMENT "ebbe a pozicióba sorolta az adott alternativát",
		  `fordulo` tinyint NOT NULL DEFAULT 0 COMMENT "szavazási forduló",
		  `ada0` tinyint NOT NULL DEFAULT 0 COMMENT "ADA regisztrált",
		  `ada1` tinyint NOT NULL DEFAULT 0 COMMENT "ADA szem.adatokat megadta",
		  `ada2` tinyint NOT NULL DEFAULT 0 COMMENT "ADA email ellenörzött",
		  `ada3` tinyint NOT NULL DEFAULT 0 COMMENT "ADA hiteles",
		  PRIMARY KEY (`id`),
		  KEY `temakori` (`temakor_id`),
		  KEY `szavazasi` (`szavazas_id`),
		  KEY `useri` (`user_id`),
		  KEY `szavazoi` (`szavazo_id`)
		)');
		$db->query();
		
		$db->setQuery('CREATE TABLE IF NOT EXISTS #__eredmeny (
			  `organization` int(11) NOT NULL DEFAULT 0 COMMENT "témakör ID",
			  `pollid` int(11) NOT NULL DEFAULT 0 COMMENT "szavazás ID",
			  `vote_count` int(11) NOT NULL DEFAULT 0 COMMENT "szavazatok száma",
			  `report` text COMMENT "cachelt report htm kód",
			  `filter` varchar(128) NOT NULL DEFAULT "" COMMENT "szavazatok rekordra vonatkozo sql filter alias:a",
			  `fordulo` tinyint NOT NULL DEFAULT 0 COMMENT "szavazási forduló",
			  `c1` int(11) NOT NULL DEFAULT 0 COMMENT "condorce elsõ helyezet alertativa ID",
			  `c2` int(11) NOT NULL DEFAULT 0 COMMENT "condorce második helyezet alertativa ID",
			  `c3` int(11) NOT NULL DEFAULT 0 COMMENT "condorce harmadik helyezet alertativa ID",
			  `c4` int(11) NOT NULL DEFAULT 0 COMMENT "condorce negyedik helyezet alertativa ID",
			  `c5` int(11) NOT NULL DEFAULT 0 COMMENT "condorce ötödik helyezet alertativa ID",
			  `c6` int(11) NOT NULL DEFAULT 0 COMMENT "condorce hatodik helyezet alertativa ID",
			  `c7` int(11) NOT NULL DEFAULT 0 COMMENT "condorce hetedik helyezet alertativa ID",
			  `c8` int(11) NOT NULL DEFAULT 0 COMMENT "condorce nyolcadik helyezet alertativa ID",
			  `c9` int(11) NOT NULL DEFAULT 0 COMMENT "condorce kilencedik helyezet alertativa ID",
			  `c10` int(11) NOT NULL DEFAULT 0 COMMENT "condorce tizedik helyezet alertativa ID"
			)
		');
		$db->query();	
	}
	
	/**
	  * egy adott szavazás adatainak beolvasása
	  * @param integer szavazas_id
	  * @return {"id":szám, "nev":string, "leiras":string, "params":object, "alternativak":[{"id":szám,"nev":string},....]} | false
	*/  
	public function getSzavazas($szavazas_id) {
		$db = JFactory::getDBO();
		$result = new stdClass();
		$result->oevkId = $szavazas_id;
		$result->oevkNev = '';
		$result->alternativak = array();
		$db->setQuery('select * from #__categories where id='.$db->quote($szavazas_id));
		$res = $db->loadObject(); 
		if ($res) {
			$result->id = $res->id;
			$result->nev = $res->title;
			$result->leiras = $res->description;
			$w = $res->metadata;
			if ($w == '') {
				// nem szavazás
				$resul = false;
				return $result;
			} else {
				$result->params = new stdClass();
				$w = JSON_encode($w);
				foreach ($this->defSzavazasParams as $fn => $fv) {
					if (isset($w->$fn))
						$result->params->$fn = $w->$fn;
					else
						$result->params->$fn = $fv;
				}
			}
			$db->setQuery('select *
			from #__content
			where catid = '.$db->quote($szavazas_id).' and metadata like "%alternativa%"
			order by title');
			$res = $db->loadObjectList();
			foreach ($res as $res1) {
				$w = new stdClass();
				$w->id = $res1->id;
				$w->nev = $res1->title;
				$result->alternativak[] = $w;
			}
		}
		return $result;
	}
	
	/**
	  * get szavazas_id alternativa_id alapján
	  * @param integer alternativa_id
	  * @return integer szavazas_id
	  */
	public function getSzavazasFromAlternativa($altId,$config) {
		$db = JFactory::getDBO();
		$result = 0;
		$db->setQuery('select * from #__content where id='.$db->quote($altId));
		$res = $db->loadObject();
		return $res->catid;
	}
	
	public function getErrorMsg() {
	  return $this->errorMsg;	
	}
	
	/**
	  * szavazat tárolása adatbázisba
	  * @param integer oevk id
	  * @param string jelolt_id=pozicio,....
	  * @param JUser
	  * @param integer fordulo
	  * @return boolean
	*/  
	public function save($szavazas_id, $szavazat, $user, $fordulo) {
		$result = true;
		$msg = '';
		if (teheti($szavazas_id, $user, 'szavazas', $msg) == false) {
			  $this->errorMsg .= $msg;
			  $result = false;
		}
		// elõ törlés
		$db = JFactory::getDBO();
		$db->setQuery('delete from #__szavazatok 
		where user_id='.$db->quote($user->id).' and fordulo='.$db->quote($fordulo).' and szavazas_id = '.$db->quote($szavazas_id));
		$db->query();
		// ada hitelesitési szint
		$ada0 = 0;
		$ada1 = 0;
		$ada2 = 0;
		$ada3 = 0;
		if (substr($user->params,0,1)=='[') $ada0 = 1;   // ADA
		if (strpos($user->params,'hash') > 0) $ada1 = 1; // ADA személyes adatok alapján
		if (strpos($user->params,'email') > 0) $ada2 = 1; // ADA email aktiválás
		if (strpos($user->params,'magyar') > 0) $ada3 = 1; // ADA személyesen ellenörzött
		// string részekre bontása és tárolás ciklusban
		$w1 = explode(',',$szavazat);
		foreach ($w1 as $item) {
			$w2 = explode('=',$item);
			$db->setQuery('INSERT INTO #__szavazatok 
				(`temakor_id`, 
				`szavazas_id`, 
				`szavazo_id`, 
				`user_id`, 
				`alternativa_id`, 
				`pozicio`,
				`ada0`, `ada1`, `ada2`, `ada3`,
				`fordulo`
				)
				VALUES
				(8, 
				'.$db->quote($szavazas_id).', 
				'.$db->quote($user->id).', 
				'.$db->quote($user->id).', 
				'.$db->quote($w2[0]).', 
				'.$db->quote($w2[1]).',
				'.$ada0.','.$ada1.','.$ada2.','.$ada3.',
				'.$db->quote($fordulo).'
				)
			');
			if ($db->query() != true) {
			  $this->errorMsg .= $db->getErrorMsg().'<br />';
			  $result = false;
			}  
		}
		
		// delete cached report
		$db->setQuery('UPDATE #__eredmeny 
		SET report="" 
		WHERE pollid='.$db->quote($szavazas_id).' and fordulo='.$db->quote($fordulo) );
		$db->query();
		return $result;
	}	
	
	public function szavazatDelete($szavazas_id, $user, $fordulo) {
		$result = true;
		$db = JFactory::getDBO();
		$db->setQuery('delete 
		from #__szavazatok 
		where user_id='.$db->quote($user->id).' and fordulo='.$db->quote($fordulo).' and szavazas_id='.$db->quote($szavazas_id));
		$result = $db->query();
		$this->errorMsg = $db->getErrorMsg();
		// delete cached report
		$db->setQuery('UPDATE #__eredmeny 
		SET report="" 
		WHERE pollid='.$db->quote($szavazas_id).' and fordulo='.$db->quote($fordulo) );
		$db->query();
		return $result;  
	}
	
	/**
	* szavazas lista olvasása az adatbázisból
	* @param integer temakor_id`
	* @return array of recordObject
	*/
	public function getSzavazasList($catid) {
		$db = JFactory::getDBO();
		$db->setQuery('select *
		from #__categories
		where parent_id = '.$catid.'
		order by title');
		$result = $db->loadObjectList();
		//DBG echo $db->getQuery();
		$this->setError($db->getErrorMsg());
		return $result;
	}
	
	/**
	* temakor rekord beolvasása az adatbázisból
	* @param integer temakor_id`
	* @return recordObject
	*/
	public function getTemakor($catid) {
		$db = JFactory::getDBO();
		$db->setQuery('select *
		from #__categories
		where id = '.$catid.'
		order by title');
		$result = $db->loadObject();
		$this->setError($db->getErrorMsg());
		return $result;
		
	}
	
	/**
	* az adott user ebben a szavazásban jogosult szavazni?
	* @param szavazasObject szavazas
	* @param JUser user
	* @return boolean
	*/
	public function szavazasraJogousult($szavazas, $user) {
		if ($user->id <=0) return false;
		if (isset($szavazas->params) == false) return false;
		if (isset($szavazas->params->ASSURANCES) == false) return false;
		$userParams = JSON_encode($user->params);
		$userAssurances = explode(',',$userParams->ASSURANCE);
		$userAssurances[] = 'registered';
		$requAssurances = explode($szavazas->params->szavazok);
		$result = false;
		foreach ($requAssurances as $reqAssurance) {
			if (in_array($reqAssurance, $userAssurances)) $result = true;
		}
		return $result;
	}
	
	/**
	* az adott user ebben a szavazásban jogosult alternativát javasolni?
	* @param szavazasObject szavazas
	* @param JUser user
	* @return boolean
	*/
	public function altJavaslatraJogousult($szavazas, $user) {
		if ($user->id <=0) return false;
		if (isset($szavazas->params) == false) return false;
		if (isset($szavazas->params->ASSURANCES) == false) return false;
		$userParams = JSON_encode($user->params);
		$userAssurances = explode(',',$userParams->ASSURANCE);
		$userAssurances[] = 'registered';
		$requAssurances = explode($szavazas->params->altJavaslok);
		$result = false;
		foreach ($requAssurances as $reqAssurance) {
			if (in_array($reqAssurance, $userAssurances)) $result = true;
		}
		// superuser
		if (is_set($user->groups[8])) $result = true;
		// elovalasztokAdmin
		if (is_set($user->groups[10])) $result = true;
		return $result;
	}

	/**
	* az adott user ezt a szavazást vitára javasolhatja?
	* @param szavazasObject szavazas
	* @param JUser user
	* @return boolean
	*/
	public function vitaraJavaslatraJogousult($szavazas, $user) {
		if ($user->id <=0) return false;
		if (isset($szavazas->params) == false) return false;
		if (isset($szavazas->params->ASSURANCES) == false) return false;
		$userParams = JSON_encode($user->params);
		$userAssurances = explode(',',$userParams->ASSURANCE);
		$userAssurances[] = 'registered';
		$requAssurances = explode($szavazas->params->vitaraJavaslok);
		$result = false;
		foreach ($requAssurances as $reqAssurance) {
			if (in_array($reqAssurance, $userAssurances)) $result = true;
		}
		return $result;
	}
		
	/**
	* az adott user ebben a szavazásban már szavazott?
	* @param szavazasObject szavazas
	* @param JUser user
	* @return boolean
	*/
	public function marSzavazott($szavazas, $user) {
		
	}
	
	/**
	* az adott user ebben a szavazásban most szavazhat?
	* @param szavazasObject szavazas
	* @param JUser user
	* @return boolean - false esetén errorMsg beállítva
	*/
	public function mostSzavzahat($szavazas, $user) {
		
	}
	
	/**
	* az adott user ebben a szavazásban most új lternativát javasolhat?
	* @param szavazasObject szavazas
	* @param JUser user
	* @return boolean  - false esetén errorMsg beállítva
	*/
	public function mostUjAlternativatJavasolhat($szavazas, $user) {
		
	}
	
	/**
	* az adott szavazást vitára javasolja
	* @param szavazasObj
	* @param JUser
	* @return boolean - hiba esetén errorMsg beállítva
	*/
	public function javasolja($szavazas, $user) {
		
	}
	
	/**
	* a vita megkezdéséhez szükséges számú javaslat 
	* @ param szavazasObj 
	* @ return integer
	*/
	public function getSzuksegesJavaslat($szavazas) {
		
	}
} 

?>