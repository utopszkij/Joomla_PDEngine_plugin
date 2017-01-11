<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$input = JFactory::getApplication()->input;
$db = JFactory::getDBO();
JHtml::_('behavior.tooltip');
$pvoksHelper = new PvoksHelper();
?>

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
						   <option vlaue="0">root</option>
						   <?php $pvoksHelper->echoCategoryOptions(0,'-&nbsp;',$this->item->category_id);  ?>
						</select>
					</div>
				</div>		

				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('user_id'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('user_id');  ?>
					</div>
				</div>		
				
				<div class="control-group">
					<div class="control-label">					
						<?php echo $this->form->getLabel('admin'); ?>
					</div>
					
					<div class="controls">	
						<?php echo $this->form->getInput('admin');  ?>
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
