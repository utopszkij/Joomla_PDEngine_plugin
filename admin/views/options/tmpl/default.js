  
   // gomb klikk
   function submitbutton(task) {
	   if (task == 'delete') {
    		 if (Joomla.boxchecked == 1) {  
    			myConfirmSubmit("<?php echo JText::_('PVOKS_SURE_DELETE'); ?>",'delete');
    		 } else {
    			myAlert("<?php echo JText::_('PVOKS_SELECT_PLEASE'); ?>","alert-error"); 
    		 }	
		} else if (task == 'edit') {
    		 if (Joomla.boxchecked == 1) {  
    	       document.forms.adminForm.task.value = task;
    	       document.forms.adminForm.submit();
    		 } else {
    		   myAlert("<?php echo JText::_('PVOKS_SELECT_PLEASE'); ?>",'alert-error');
    		 }	
		} else {
			document.forms.adminForm.task.value = task;
			document.forms.adminForm.submit();
		}
   }

   /**
     *   sor klikk edit task hivása az aktuális ablakba
   */	 
   function itemClick(id) {
	   document.forms.adminForm.id.value = id;
	   document.forms.adminForm.task.value = 'edit';
	   document.forms.adminForm.submit();
   }
