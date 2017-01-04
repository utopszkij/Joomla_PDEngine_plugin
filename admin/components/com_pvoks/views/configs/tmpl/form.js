// mvc_editor component components view form javascript code

   function submitbutton(task) {
	   if (task == 'save') {
		 if (formValidator()) {
	       document.forms.adminForm.task.value = task;
	       document.forms.adminForm.submit();
         }			 
	   } else if (task == 'savenew') {
		 if (formValidator()) {
	       document.forms.adminForm.task.value = task;
	       document.forms.adminForm.submit();
         }			 
	   } else {
	     document.forms.adminForm.task.value = task;
	     document.forms.adminForm.submit();
	   }
   }
   
   function formValidator() {
	   // form ellenörzése
	   // ha hibás: szepAlert(hibaüzenet), field.class += ' invalid', return false
	   // ha hibátlan return true
	   var result = true;
	   var msg = '';
	   if (jQuery('#jform_title').val() == "") {
		   msg += '<?php echo JText::_('PVOKS_CONFIG_TITLE_REQUED'); ?> ';
		   jQuery('#jform_title').addClass('invalid');
	   } else {
		   jQuery('#jform_title').removeClass('invalid');
	   }
	   if (jQuery('#jform_json').val() == "") {
		   msg += '<?php echo JText::_('PVOKS_CONFIG_JSON_REQUED'); ?> ';
		   jQuery('#jform_json').addClass('invalid');
	   } else {
		   jQuery('#jform_json').removeClass('invalid');
	   }
	   if (msg != '') {
		   result = false;
		   myAlert(msg,'alert-error');
	   }
	   return result;
   }
   
   jQuery(
	   function () {
		   // form onActivate
		   jQuery('#jform_title').focus();
	   }
     
   );
