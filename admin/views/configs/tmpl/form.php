<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$input = JFactory::getApplication()->input;
JHtml::_('behavior.tooltip');
?>

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
<form method="post" action="<?php echo JRoute::_('index.php?option=com_pvoks&view=configs&id='.(int) $this->item->id);  ?>" id="adminForm" name="adminForm">
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

json global és "category" esetén
--------------------------------
	{"defCategorySuggestiion":Y/N,
	 "defQuestionSuggestion":Y/N,
	 "defOptionSuggestion":Y/N,
	 "defMemberSuggestion":Y/N,
	 "defQuestionType":szám,
	 "acrediteEnabled": Y/N,
	 "groupactions":["group" => ["xxxx","xxxx",......]
	 "scripts":["event" => "script_id",...],
	 "lngStrings":["token" => "xxxxxx",....]
	}


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
view-comment  A szavazáshoz tartozó kommentek megjelenítése. Ez egy “gyári” joomla extension,  a “nick” nevet jeleniti meg a kommenteknél.
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
question-suggestion-support  Ötlet “szavazásra, vitára javaslom” bejelölése, illetve ennek visszavonása
question-edit  Alternatíva módosítása (ha ez a lehetőség adott akkor adminok minden alternativát, mások csak a saját maguk által feltöltöttet módosíthatnak)
question-delete  Alternatíva törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
question-merge  Két alternatíva összevonása

option-suggestion-edit  Ötlet modosítása (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet módosíthatnak)
option-suggestion-delete  Ötlet törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
option-suggestion-merge  Ötletek összevonása
option-suggestion-support  Ötlet “szavazásra, vitára javaslom” bejelölése, illetve ennek visszavonása
option-edit  Alternatíva módosítása (ha ez a lehetőség adott akkor adminok minden alternativát, mások csak a saját maguk által feltöltöttet módosíthatnak)
option-delete  Alternatíva törlése (ha ez a lehetőség adott akkor adminok minden suggestiont, mások csak a saját maguk által feltöltöttet törölhetnek)
option-merge  Két alternatíva összevonása

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
