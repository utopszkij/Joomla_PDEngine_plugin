<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$input = JFactory::getApplication()->input;
$db = JFactory::getDBO();
JHtml::_('behavior.tooltip');
$pvoksHelper = new PvoksHelper();
?>

<style type="text/css">
.examples {background-color: #E0F0E0}
</style>

<div style="clear:both"></div>
<h2><i class="icon-users"></i><?php echo $this->title; ?></h2>
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
						<?php echo $this->form->getLabel('category_id'); ?>
					</div>
					
					<div class="controls">	
						<select name="jform[category_id]" id ="jform_category_id" class="requed">
						   <option value="0">root</option>
						   <?php $pvoksHelper->echoCategoryOptions(0,'-&nbsp;',$this->item->category_id);  ?>
						</select>
					</div>
				</div>		

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
						<?php echo $this->form->getLabel('alias'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('alias');  ?>
					</div>
				</div>		
				
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('question_type'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('question_type');  ?>
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
						<?php echo $this->form->getLabel('text'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('text');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('publicvote'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('publicvote');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('acredite_enabled'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('acredite_enabled');  ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('target_category_id'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('target_category_id');  ?>
					</div>
				</div>	

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('termins'); ?><br>
						<div class="examples">
						examples:<br>
						2017-12-31<br>
						30days<br />
						lastSuggestionDate < date(-20)
						</div>
					</div>
					<div style="float:left; display:block; width:auto; max-width:400px;">	
						<ul class="steps" style="margin-top:3px;">
						<?php if (is_array($this->item->qtype->json->steps))
						  foreach ($this->item->qtype->json->steps as $i => $step) : ?>
						  <li><?php echo (1+$i).' '.$step->title; ?></li>
						<?php endforeach; ?>
						</ul>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('termins');  ?>
					</div>
					
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('optvalid'); ?>
						<div class="examples">
						examples:<br />
						100<br />
						voterCount * 0.1<br />
						sqrt(voterCount) / 2
						</div>
					</div>
					<div class="controls">	
						<?php echo $this->form->getInput('optvalid');  ?>
						<?php echo JText::_('PVOKS_SUPPORT'); ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('debatevalid'); ?>
						<div class="examples">
						examples:<br />
						100<br />
						voterCount * 0.5<br />
						sqrt(voterCount) * 2
						</div>
					</div>
					<div class="controls">	
						<?php echo $this->form->getInput('debatevalid');  ?>
						<?php echo JText::_('PVOKS_SUPPORT'); ?>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('votevalid'); ?>
						<div class="examples">
						examples:<br />
						100<br />
						voterCount * 0.5<br />
						sqrt(voterCount) * 2
						</div>
					</div>
					<div class="controls">	
						<?php echo $this->form->getInput('votevalid');  ?>
						<?php echo JText::_('PVOKS_VOKS'); ?>
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
		<input type="hidden" name="view" value="questions" />
		<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
		<input type="hidden" name="limit" value="<?php echo $input->get('limit',20); ?>" />
		<input type="hidden" name="limitstart" value="<?php echo $input->get('limitstart',0); ?>" />
		<input type="hidden" name="filter_str" value="<?php echo $input->get('filter_str','','string'); ?>" />
		<input type="hidden" name="filter_category_id" value="<?php echo $input->get('filter_category_id',0); ?>" />
		<input type="hidden" name="filter_user_id" value="<?php echo $input->get('filter_user_id',0); ?>" />
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
