<?php
/**
  * szavazok component
  * taskok: szavazok, szavazatdelete, eredmeny, szavazatsave, szavazaslist
  * Licensz: GNU/GPL
  * Szerző: Fogler Tibor   tibor.fogler@gmail.com_addref
  * web: github.com/utopszkij/elovalasztok2018
  * Verzió: V1.00  2016.09.14.
  *
  */
  global $config;
  include_once JPATH_SITE.'/components/com_pvoks/accesscontrol.php';
  include_once JPATH_SITE.'/components/com_pvoks/funkciok.php';
  
  // ================ controller ==============================
  class PvoksController extends JControllerLegacy {
	  
	protected $defCatid = 8; // default szavazás kategória
	
	/**
	* szavazó form kirajzolása
	* @JRequest integr 'id' = szavazas_id
	*/
    public function szavazok() {
		global $config;
		$db = JFactory::getDBO();
		$input = JFactory::getApplication()->input;  
		$user = JFactory::getUser();
		$szavazas_id = $input->get('id',0);

		$msg = '';
		if ($szavazas_id <= 0) {
			echo '<div class="warning">Válassza ki a szavazást!</div>';
			$this->szavazaslist(0);
		} else if ($user->id > 0) {
		   if (szavazottMar($szavazas_id, $user)) {
			  echo '<div class="warning">Ön már szavazott!</div>';
		   } else {
		    if (teheti($szavazas_id, $user, 'szavazas', $msg)) {
		      $model = $this->getModel('pvoks');
		      $item = $model->getSzavazas($szavazas_id);	
			  if (count($item->alternativak) <= 0) {
			    echo '<div class="warning">Nincs egyetlen jelölt sem.</div>';
			  } else {
			    $view = $this->getView('pvoks','html');	  
				$view->set('item',$item);
				$view->setLayout('szavazoform');
				$view->display();
			  }  
		    } else {
			  echo '<div class="error">Jelenleg nem szavazhat</div>';
			}	   
		  }	 
		} else {
			// nincs bejelentkezve
			$url = JURI::base().'index.php?option=com_contnet&view=category&id='.$db->quote($szavazas_id);
			$loginURL = JURI::base().'index.php?option=com_adalogin&redi='.base64url_encode2($url);
			$this->setRedirect($loginURL);
			$this->redirect();
		}
	}

	/**
	* szavazat törlése
	* @JRequest integr 'id' = szavazas_id
	*/
    public function szavazatdelete() {
		global $config;
		$input = JFactory::getApplication()->input;  
		$user = JFactory::getUser();
		$szavazas_id = $input->get('id',0);
		$msg = '';
		if ($szavazas_id <= 0) {
			echo '<div class="warning">Válassza ki a szavazást!</div>';
			$this->szavazaslist(0);
		} else if ($user->id > 0) {
		   if (szavazottMar($szavazas_id, $user) == false) {
			  echo '<div class="warning">Ön még nem szavazott!</div>';
		   } else {
		    if (teheti($szavazas_id, $user, 'szavazatDelete', $msg)) {
		      $model = $this->getModel('pvoks');
			  if ($model->szavazatDelete($szavazas_id, $user, $config->fordulo)) {
				  $msg = 'szavazata törölve lett.';
				  $msgClass = 'info';
			  } else {
				  $msg = $model->getErrorMsg();
				  $msgClass = 'error';
			  }
		      $this->setMessage($msg,$msgClass);
			  $this->setRedirect('index.php?option=com_content&view=category&layout=articles&id='.$szavazas_id);
			  $this->redirect();
		    } else {
			  echo '<div class="error">Jelenleg nem módosíthatja szavazatát</div>';
			}	   
		  }	 
		} else {
			// nincs bejelentkezve
			$url = JURI::base().'index.php?option=com_contnet&view=category&id='.$db->quote($szavazas_id);
			$loginURL = JURI::root().'index.php?option=com_adalogin&redi='.base64url_encode2($url);
			$this->setRedirect($loginURL);
			$this->redirect();
		}
	}

	/**
	* szavazás eredményének megjelenítése
	* JRequest integer 'id' = szavazas_id
	*/
    public function eredmeny() {
		global $config;
		$db = JFactory::getDBO();
		$input = JFactory::getApplication()->input;  
		$user = JFactory::getUser();
		$szavazas_id = $input->get('id',0);
		$model = new szavazokModel();
		$filter = '';
		if ($szavazas_id <= 0) {
			echo '<div class="warning">Válassza ki a szavazást!</div>';
			$this->szavazaslist(0);
		} else {
			include_once JPATH_ROOT.'/components/com_pvoks/condorcet.php';
			$backUrl = JURI::base().'/index.php?option=com_content&view=category&id='.$szavazas_id;
			$organization = '';
			$db->setQuery('select * from #__categories where id='.$db->quote($szavazas_id));
			$poll = $db->loadObject();
			echo '<h2>'.$poll->title.'</h2>
			';
			$pollid = $szavazas_id;
			
			// nézzük van-e cachelt report?
			$db->setQuery('select * from 
			               #__eredmeny 
						   where pollid='.$db->quote($pollid).' and 
			                     filter='.$db->quote($filter).' and
								 fordulo = '.$db->quote($config->fordulo) );
			$cache = $db->loadObject();
			
			// ha nincs meg a cache rekord akkor hozzuk most létre, üres tartalommal
			if ($cache == false) {
			  $db->setQuery('INSERT INTO #__eredmeny
			  (pollid, report,filter,fordulo ) 
			  value 
			  ('.$db->quote($pollid).',"","'.$filter.'",'.$db->quote($config->fordulo).')');
			  $db->query();
			  $cache = new stdClass();
			  $cache->pollid = $pollid;
			  $cache->filter = $filter;
			  $cache->fordulo = $config->fordulo;
			  $cache->report = "";
			}
			if ($cache->report == "") {
			  // ha nincs; most  kell condorcet/Shulze feldolgozás feldolgozás
			  $schulze = new Condorcet($db,$organization,$pollid,$filter,$config->fordulo);
			  $report = $schulze->report();
			  $db->setQuery('update #__eredmeny 
			  set report='.$db->quote($report).',
			      c1 = '.$schulze->c1.',
			      c2 = '.$schulze->c2.',
			      c3 = '.$schulze->c3.',
			      c4 = '.$schulze->c4.',
			      c5 = '.$schulze->c5.',
			      c6 = '.$schulze->c6.',
			      c7 = '.$schulze->c7.',
			      c8 = '.$schulze->c8.',
			      c9 = '.$schulze->c9.',
			      c10 = '.$schulze->c10.',
				  vote_count = '.$schulze->vote_count.'
			  where pollid="'.$pollid.'" and filter='.$db->quote($filter).' and fordulo='.$db->quote($config->fordulo));
			  $db->query();
			} else {  
			  // ha van akkor a cahcelt reportot jelenitjuük meg
			  $report = $cache->report; 
			}
			$view = $this->getView('pvoks','html');
			$view->setLayout('eredmeny');
			$view->display();
		} // szavazazas_id adott
	} // eredmeny function

	/**
	  * sazavazás képernyő adat tárolása
	  * JRequest: token, szavazas_id, szavazat: jelölt_id=pozicio, ......
	  */
	public function szavazatsave() {
		global $config;
		Jsession::checkToken() or die('invalid CSRF protect token');
		if ($user->id <= 0) die('nincs bejelentkezve, vagy lejárt a session');
		$input = JFactory::getApplication()->input;  
		$user = JFactory::getUser();
		$szavazas_id = $input->get('szavazas_id','','STRING');
		$szavazat = $input->get('szavazat','','STRING');
		$msg = '';
		$msgClass = '';
		if ($szavazas_id > 0) {
			if (szavazottMar($szavazas_id, $user, $config->fordulo))
				$akcio = 'szavazatEdit';
			else
				$akcio = 'szavazas'; 
			if (teheti($szavazas_id, $user, $akcio, $msg)) {
				$model = $this->getModel('pvoks');
				if ($model->save($szavazas_id, $szavazat, $user, $config->fordulo)) {
					$msg = 'Köszönjük szavazatát.';
				    $msgClass = 'info';
				} else {
					$msg = $model->getErrorMsg();
					$msgClass = 'error';
				}	
			} else {
				$msgClass = 'error';
			}	
		} else {
			$msg = 'Nincs kiválasztva a szavazás';
			$msgClass = 'error';
		}
		if ($msg != '')
		$this->setMessage($msg,$msgClass);
        $this->setRedirect('index.php?option=com_content&view=category&id='.$szavazas_id);
		$this->redirect();
	} // szavzasSave function
	
	/**
	* szavazás lista kirajzolása a képernyőre
	* @JRequest integer 'temakor_id'
	*/
	public function szavazaslist() {
		$db = JFactory::getDBO();
		$input = JFactory::getApplication()->input;  
		$catid = $input->get('temakor_id',0);
		if ($catid == 0) $catid = $this->defCatid;
		$model = $this->getModel('pvoks');
		$items = $model->getSzavazasList($catid);
		$temakor = $model->getTemakor($catid);
		$view = $this->getView('pvoks','html');
		$view->set('items',$items);
		$view->set('temakor',$temakor);
		$view->setLayout('szavazaslist');
		$view->display();
	}
  }
  
?>
