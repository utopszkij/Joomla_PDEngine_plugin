<?php
/**
  * szavazok component
  *   taskok: szavazok, szavazatEdit, szavazatDelete, eredmeny, szavazatSave
  * Licensz: GNU/GPL
  * Szerző: Fogler Tibor   tibor.fogler@gmail.com_addref
  * web: github.com/utopszkij/elovalasztok2018
  * Verzió: V1.00  2016.09.14.
  *
  * Akció kódok:
  * ============
  * view-category  Ketegoria megtekintése
  * view-category-suggestion  Ketegoria javaslat megtekintése
  * view-subcategories  Alkategoria lista megtekintése
  * view-subcategory-suggestions Alkategoria javaslat lista megtekintése
  * view_questions-list szavazás lista megjelenitése
  * view->question-sugesstions-list  szavazás javaslat lista megjelenítése
  *
  * view-question  Szavazás megtekintése
  * view-question-suggestion  Szavazás ötletek  megtekintése
  *	view-option-sugestion Ötletek listájának és bővebb leírásának megtekintése
  *	view-option    Alternatívák listájának és bővebb leírásának a megtekintése
  *	view-creator-nick Az ötlet beküldő nick nevének megjelenítése
  *	view-creator-name Az ötlet beküldő valódi nevének megjelenítése
  *	view-comment   A szavazáshoz tartozó kommentek megjelenítése. Ez egy “gyári” joomla extension,  a “nick” nevet jeleniti meg a kommenteknél.
  *	view-votecount A leadott szavazatok számának kijelzése
  *	view-result    A szavazás eredményének vagy pillanatnyi részeredményének megjelenitése (alternativák condorcet sorrendben)
  *	view-votes     A leadott konkrét szavazatok megjelenitése a szavazó nick nevének feltüntetésével (nyílt szavazás)
  *
  * category-suggestion_add  Új kategória javaslat felvitele
  * category-suggestion_edit Kategória javaslat modositása
  * category-suggestion_delete  Kategória javaslat törlése
  * category-suggestion-merge   Kategória javaslatok összevonása
  * category-suggestion_support  Kategória javaslat támogatása
  * category-suggestion_support-delete  Kategória javaslat visszavonása
  * category-add   Kategória felvitele
  * category-edit  Kategória modosítása
  * category-delete  Kategória törlése
  * category-merge   kategóriák összevonása
 
  * question-suggestion-add Új szavazás javaslat felvitele
  * question-suggestion-edit Szavazás javaslat modosítása
  * question-suggestion-delete szavazás javaslat törlése
  * question-suggestion-merge két szavazás javaslatok összevonása
  * question-suggestion-support  Szavazás javaslat támogatása
  * question-suggestion_support-delete  Szavazás javaslat támogatásának törlése
  * question-add Új szavazás inditása  Új szavazás inditása
  * question-edit szavazás modosítása  Szavazás modosítása
  * question-delete szavazás törlése   Szavazás törlése
  * question-merge két szavazás összevonása  Szavazások összevonása
  
  *	option_suggestion-add Új ötlet beküldése
  *	option_suggestion-edit Ötlet modosítása (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet módosíthatnak)
  *	option_suggestion-delete Ötlet törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
  *	option_suggestion-merge Ötletek összevonása
  *	option_suggestion-support Ötletek támogatása
  * option_suggestion-support-delete Ötlet támogatás visszavonása
  * option_add  Alternativa felvitele
  *	option-edit Alternativa modosítása (ha ez a lehetőség adott akkor adminok minden optionst, mások csak a saját maguk által feltöltöttet módosíthatnak)
  *	option-delete Alternativa törlése (ha ez a lehetőség adott akkor adminok minden optionst, mások csak a saját maguk által feltöltöttet törölhetnek)
  *	option-merge Alternativák összevonása*
  
  * vote-add  szavazat beküldése
  * vote-delete  saját szavazat törlése
  
  * comment-add  kommentelés
  *
  * UserGroups
  * ==========
  * gr###    Joomla user csoport
  * adaxxxx  ADA assurance
  *
  * Category {"id"#,"title":"xxx","description":#,"parent":#, "type":#, "suggestion":#, qeuerys:[Query,...]
  *	  "right":["usergroup" => ["akcio",....]], "supportCount":#, "supports":[Support, Support,...], "scripts":[Script,...]
  * }
  *
  * Question {
  *    "id":#, "catid":#, "title":"xxx","description":"xxx","status":#,"steps":[Step,Step...],"suggestion"#,
  *    "publicVoks":#, "target".#, "prior":#, "rule".#,
  *    "scripts":[Script,...],
  *    "supportCount":#, "supports":[Support, Support...]
  *    "aliasCount":#, "aliases":[Alias,...], 
  *    "voteCount":#, "votes":[Vote, Vote,...] 
  * }
  * Step {"question_id":#, "title":"xxx",
  *       "deadline":"ééé-hh-nn",
  *       "userGroups":["akció, akció,..."]  
  * }
  * option {
  *	   "id":#,"question_id":#, "status":#, "title":"xxx","description":"xxx","creator":#,"alternativeTime":##,    
  *        "supportCount":#, "supports":[Support,...]  
  * }
  * Vote {
  *   "id":#,"question_id":#,"option_id":#,"voter_id":# 	  
  *}
  * Support {"category_id":#, "question_id":#, "option_id":#, "supporter_id":#}
  *
  * Script {
  *	  "id":#,"event":"xxxx",.....  
  * }
  * a scriptek előre megirt választékból választhatóak
  *
  * Néhány fontosabb funkció
  * ========================
  * bool = access($category, $question=0, $user, $akcio)
  * szám = getUserCount($category, $question=0, akció)  az akcióra  jogoultak száma
  *
  */
  include_once JPATH_SITE.'/components/com_pvoks/controller.php';
  include_once JPATH_SITE.'/components/com_pvoks/funkciok.php';
  $input = JFactory::getApplication()->input;  
  $task = $input->get('task','szavazaslist');
  $controller = new pvoksController();
  $controller->$task ();
?>
