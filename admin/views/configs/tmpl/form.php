<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$input = JFactory::getApplication()->input;
JHtml::_('behavior.tooltip');
?>

<style type="text/css">
.akcioChk {display:inline-block; width:250px;}
.step {border-style:solid; border-width:1px; margin:1px 3px 1px 1px; cursor:n-resize; list-style:none}
.group {list-sryle:none; border-style:solid; border-width:1px; margin:1px 3px 1px 1px; list-style:none}
.actionPopup {display:none; position:fixed; left:50px; top:100px; z-index:99; background-color:#E0E0e0; 
              padding:10px; border-style:solid; border-width:1px; width:auto}
fieldset {display:inline-block; width:260px; margin-top:10px;}
</style>

<script src="<?php echo JURI::root(); ?>media/jui/js/jquery.ui.core.min.js" type="text/javascript"></script>
<script src="<?php echo JURI::root(); ?>media/jui/js/jquery.ui.sortable.min.js" type="text/javascript"></script>
<div style="clear:both"></div>
<h2><i class="icon-tools"></i>&nbsp;<?php echo $this->title; ?></h2>
<div style="clear:both"></div>
<div class="buttons">
<?php foreach ($this->buttons as $button) : ?>
	 <button type="button" class="btn <?php echo $button[2]; ?>" onclick="submitbutton('<?php echo $button[0]; ?>');">
		<i class="<?php echo $button[3]; ?>"></i><span><?php echo $button[1]; ?></span>
	 </button>
<?php endforeach; ?>
</div>
<div style="clear:both"><br /></div>
<form method="post" action="<?php echo JRoute::_('index.php?option=com_pvoks&view=configs&id='.(int) $this->item->id);  ?>" 
      id="adminForm" name="adminForm" onsubmit="JSONEditor.scrToTXT(); true;">
	 	<div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-60  <?php endif; ?>span8 form-horizontal fltlft">
		  <fieldset class="adminform">
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('title'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('title');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('config_type'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('config_type');  ?>
					</div>
				</div>		

				
				<div id="questionConfig">
				  <p>Extra language file:
				  <select id="extraLngFile">
				    <option value="">Nincs</option>
				    <option value="oevk">oevk</option>
				  </select>
				  Plugin:
				  <select id="plugin">
				    <option value="">Nincs</option>
				    <option value="debian">debian</option>
				  </select>
				  </p>
				  <ul id="ulSteps">
				  </ul>
				  <button type="button" id="addStep" title="add step"><i class="icon-plus"></i>Add step</button>&nbsp;
				</div>				
				
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('json'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('json');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('state'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('state');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('created'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('created');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('created_by'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('created_by');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('modified'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('modified');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('modified_by'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('modified_by');  ?>
					</div>
				</div>		
          </fieldset>                      
        </div>
        <div class="col <?php if(version_compare(JVERSION,'3.0','lt')):  ?>width-30  <?php endif; ?>span2 fltrgt">
        </div>                   
		<input type="hidden" name="option" value="com_pvoks" />
	    <input type="hidden" name="cid[]" value="<?php echo $this->item->id ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="view" value="configs" />
		<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
		<input type="hidden" name="limit" value="<?php echo $input->get('limit',20); ?>" />
		<input type="hidden" name="limitstart" value="<?php echo $input->get('limitstart',0); ?>" />
		<input type="hidden" name="filter_str" value="<?php echo $input->get('filter_str','','string'); ?>" />
		<input type="hidden" name="filter_order" value="<?php echo $input->get('filter_order'); ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $input->get('filter_order_Dir'); ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
</form>
<div style="clear:both"></div>
<div class="buttons">
<?php foreach ($this->buttons as $button) : ?>
	 <button type="button" class="btn <?php echo $button[2]; ?>" onclick="submitbutton('<?php echo $button[0]; ?>');">
		<i class="<?php echo $button[3]; ?>"></i><span><?php echo $button[1]; ?></span>
	 </button>
<?php endforeach; ?>
<div class="info">
<pre><code>

json a question_type esetén:
----------------------------
	{"steps":{"title":"xxxxxx", "groupactions":["group" => ["xxxx","xxxx",......],"termin":"szám vagy képlet"]},
	 "scripts":["event" => "script_id",...],
	 "priority":sos/normal,
	 "lngStrings":["token" => "xxxxxx",....]
	}


"group" felhasználói csoportok
------------------------------
guest  látogató, nincs bejelentkezve
registered  ADA -val be van jelentkezve, de semmilyen tanúsítványa nincs,
ada-email email ellenőrzött ADA bejelentkezés. 
ada-magyar Magyar tanusítvánnyal rendelkező ADA bejelntkezés. 
ada-oevk Magyar tanusítvánnyal és egy oevk tanusítvánnyal rendelkező ADA bejelentkezés 
ada-vm  Magyar, oevk és választói mozgalom tanusítvánnyal rendelkező ADA bejelentkezés. 
category-creator  az aktuális category létrehozója
question-creator  az aktuális szavazás létrehozója
option-creator  az aktuális opció létrehozója
category-candidates  tagságra jelentkezett
category-member  az aktuális kategória tagja
category-admin  az aktuális kategória adminisztrátora
category-acredited képviselő
admin adminisztrátor. 

"action" kodok tranzakciók 
--------------------------
view-categories
view-members
view-sugestion Ötletek listájának és bővebb leírásának megtekintése
view-alternative  Alternatívák listájának és bővebb leírásának a megtekintése
view-creator-nick 	Az ötlet beküldő nick nevének megjelenítése
view-creator-name  	Az ötlet beküldő valódi nevének megjelenítése
view-comment  A szavazáshoz tartozó kommentek megjelenítése. 
view-vokscount  A leadott szavazatok számának kijelzése
view-result  A szavazás eredményének vagy pillanatnyi részeredményének megjelenitése (alternativák condorcet sorrendben).

category-add   Új kategória (javaslat) beküldése
question-add  Új szavazási ötlet beküldése.
option-add    Új megoldási ötlet beküldése.
member-add    Új témakör tagság

category-suggestion-edit  Ötlet modosítása (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet módosíthatnak)
category-suggestion-delete  Ötlet törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
category-suggestion-merge  Ötletek összevonása
category-suggestion-support  Ötlet “szavazásra, vitára javaslom” bejelölése, illetve ennek visszavonása
category-edit  Alternatíva módosítása (ha ez a lehetőség adott akkor adminok minden alternativát, mások csak a saját maguk által feltöltöttet módosíthatnak)
category-delete  Alternatíva törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
category-merge  Két alternatíva összevonása

question-suggestion-edit  Ötlet modosítása (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet módosíthatnak)
question-suggestion-delete  Ötlet törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
question-suggestion-merge  Ötletek összevonása
question-suggestion-support  Ötlet “szavazásra, javaslom” bejelölése, illetve ennek visszavonása
question-edit  Alternatíva módosítása (ha ez a lehetőség adott akkor adminok minden alternativát, mások csak a saját maguk által feltöltöttet módosíthatnak)
question-delete  Alternatíva törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
question-merge  Két alternatíva összevonása
question-vote-support Szavazás megnyitásának támogatása

option-suggestion-edit  Ötlet modosítása (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet módosíthatnak)
option-suggestion-delete  Ötlet törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
option-suggestion-merge  Ötletek összevonása
option-suggestion-support  Ötlet “szavazásra, vitára javaslom” bejelölése, illetve ennek visszavonása
option-edit  Alternatíva módosítása (ha ez a lehetőség adott akkor adminok minden alternativát, mások csak a saját maguk által feltöltöttet módosíthatnak)
option-delete  Alternatíva törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
option-merge  Két alternatíva összevonása
option-support Vita megnyitás támogatása

member-suggestion-edit
member-suggestion-delete
member-edit
member-delete
member-suggestion-support

comment-add  Komment beküldése (a “gyári” extension adminok számára mindig megengedi a moderálást)
voks-add  szavazás (alternativás szimpátia sorrendbe rendezése)
voks-delete  Saját leadott szavazatom törlése
set-step  	Szavazás állapotának modosítása

"event" esemény kodok (ezek a pvoksHelperScripts -ben lévő funkciokat fogják aktivizálni)
----------------------
afterCategorySupport
afterQuestionSupport
afterOptionSupport
afterMemberSupport
afterVoksAdd
afterVoksDelete
getRequestCategorySupport  
getRequestedQuestionSupport         
getRequestedOptionSupport            
getRequestedMemberSupport           
getTermin(stepNo)
getValidLimit      
getValid           
oncron

</code></pre>
</div>
</div>

<div style="display:none">
  <div id="step0" >
    <li class="step">
	  Step title:<input type="text" style="width:500px" value="step" onchange="JSONEditor.scrToTXT()" />
	  <button class="btnDelStep" type="button" title="delete step" style="float:right"><i class="icon-delete"></i>Delete step</button>
	  <ul>
	  </ul>
      <button class="btnAddGroup" type="button" title="add group"><i class="icon-plus"></i>Add group</button>
	</li>		  
  </div>
  
  <div id="group0" >
    <li class="group">
			  User group:
			  <select size="1">
                <option value="guest">látogató, nincs bejelentkezve</option>
                <option value="registered">Be van jelentkezve, semmijen tanusitása nincs,</option>
                <option value="ada-email">email ellenőrzött ADA bejelentkezés. </option>
                <option value="ada-magyar">Magyar tanusítvánnyal rendelkező ADA bejelntkezés. </option>
                <option value="ada-oevk">Magyar tanusítvánnyal és egy oevk tanusítvánnyal rendelkező ADA bejelentkezés </option>
                <option value="ada-vm"> Magyar, oevk és választói mozgalom tanusítvánnyal rendelkező ADA bejelentkezés. </option>
                <option value="category-creator">az aktuális category létrehozója</option>
                <option value="question-creator">az aktuális szavazás létrehozója</option>
                <option value="option-creator">az aktuális opció létrehozója</option>
                <option value="category-candidates">tagságra jelentkezett</option>
                <option value="category-member">az aktuális kategória tagja</option>
                <option value="category-admin">az aktuális kategória adminisztrátora</option>
                <option value="category-acredited">képviselő</option>
                <option value="admin">adminisztrátor. </option>
			  </select>
			  <button class="btnEditActions" type="button" title="edit actions"><i class="icon-edit"></i>Edit actions</button>
			  <button class="btnDelGroup" type="button" title="delete group" style="float:right"><i class="icon-delete"></i>Delete group</button>
			  <br />
			  <span> </span>
	</li>		  
  </div>
</div>

<div id="actionsPopup" class="actionPopup">
  <p>
   <b id="actionsPopupTitle"></b>
   <button style="float:right;" type="button" onclick="jQuery('#actionsPopup').hide();" title="close"><i class="icon-delete"></i></button>
  </p> 
<fieldset>
<div class="akcioChk"><input type="checkbox" value="view-categories" /></div>
<div class="akcioChk"><input type="checkbox" value="view-questions" /></div>
<div class="akcioChk"><input type="checkbox" value="view-members" /></div>
<div class="akcioChk"><input type="checkbox" value="view-options" /></div>
<div class="akcioChk"><input type="checkbox" value="view-category-sugestions" /></div>
<div class="akcioChk"><input type="checkbox" value="view-question-sugestions" /></div>
<div class="akcioChk"><input type="checkbox" value="view-option-sugestions" /></div>
<div class="akcioChk"><input type="checkbox" value="view-creator-nick" /></div>
<div class="akcioChk"><input type="checkbox" value="view-creator-name" /></div>
<div class="akcioChk"><input type="checkbox" value="view-comment" /></div>
<div class="akcioChk"><input type="checkbox" value="view-vokscount" /></div>
<div class="akcioChk"><input type="checkbox" value="view-result" /></div>
<div class="akcioChk"><input type="checkbox" value="view-myvoks" /></div>
<div class="akcioChk"><input type="checkbox" value="view-vokses" /></div>
</fieldset>
<fieldset>
<div class="akcioChk"><input type="checkbox" value="add-category-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="add-question-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="add-option-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="add-member-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="add-category" /></div>
<div class="akcioChk"><input type="checkbox" value="add-question" /></div>
<div class="akcioChk"><input type="checkbox" value="add-option" /></div>
<div class="akcioChk"><input type="checkbox" value="add-member" /></div>
<div class="akcioChk"><input type="checkbox" value="add-acredited" /></div>
</fieldset>
<fieldset>
<div class="akcioChk"><input type="checkbox" value="edit-category-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="edit-question-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="edit-option-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="edit-member-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="edit-category" /></div>
<div class="akcioChk"><input type="checkbox" value="edit-question" /></div>
<div class="akcioChk"><input type="checkbox" value="edit-option" /></div>
<div class="akcioChk"><input type="checkbox" value="edit-member" /></div>
<div class="akcioChk"><input type="checkbox" value="edit-acredited" /></div>
</fieldset>
<fieldset>
<div class="akcioChk"><input type="checkbox" value="delete-category-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="delete-question-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="delete-option-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="delete-member-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="delete-category" /></div>
<div class="akcioChk"><input type="checkbox" value="delete-question" /></div>
<div class="akcioChk"><input type="checkbox" value="delete-option" /></div>
<div class="akcioChk"><input type="checkbox" value="delete-member" /></div>
<div class="akcioChk"><input type="checkbox" value="delete-acredited" /></div>
</fieldset>
<fieldset>
<div class="akcioChk"><input type="checkbox" value="support-category-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="support-question-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="support-option-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="support-member-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="support-vote-start" /></div>
</fieldset>
<fieldset>
<div class="akcioChk"><input type="checkbox" value="merge-category-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="merge-question-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="merge-option-suggestion" /></div>
<div class="akcioChk"><input type="checkbox" value="merge-category" /></div>
<div class="akcioChk"><input type="checkbox" value="merge-question" /></div>
<div class="akcioChk"><input type="checkbox" value="merge-option" /></div>
</fieldset>
<fieldset>
<div class="akcioChk"><input type="checkbox" value="comment-add" /></div>
<div class="akcioChk"><input type="checkbox" value="comment-edit" /></div>
<div class="akcioChk"><input type="checkbox" value="comment-delete" /></div>
<div class="akcioChk"><input type="checkbox" value="voks-add" /></div>
<div class="akcioChk"><input type="checkbox" value="voks-delete" /></div>
<div class="akcioChk"><input type="checkbox" value="set-step" /></div>
</fieldset>
<br />
<center><button type="button" id="actionsPopupOK"><i class="icon-ok"></i>Rendben</button></center>
</div>

