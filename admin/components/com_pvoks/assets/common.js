/**
* pvoks component common javascript
* Authir: Fogler Tibor
* Author-email: tibor.fogler@gmail.com
* Author-web: https://github.com/utopszkij
* Licence: GNU/GPL
*/
	if (typeof Joomla == 'undefined') {	
	    Joomla = {};
	}	
	Joomla.boxchecked = false;
	Joomla.isChecked = function (value) {
	   Joomla.boxchecked = value;
	};

	function myConfirmSubmit(msg, task)
	{
		var confirmBox = jQuery("#confirmBox");
		confirmBox.find(".message").html(msg);
		confirmBox.find(".yes,.no").unbind().click(function()
		{
			confirmBox.hide();
		});
		confirmBox.find(".yes").click(function() {document.forms.adminForm.task.value = task; document.forms.adminForm.submit();});
		confirmBox.find(".no").click(function() {});
		confirmBox.show();
	}

	function myAlert(msg, msgClass="")
	{
		var confirmBox = jQuery("#alertBox");
		confirmBox.find(".message").html(msg);
		confirmBox.addClass(msgClass);
		confirmBox.find(".yes,.no").unbind().click(function()
		{
			confirmBox.hide();
		});
		confirmBox.find(".yes").click(function() {});
		confirmBox.show();
	}
   
   
   /**
   * táblázat fejlécben klikk
   * @param string új rendezés field
   * @param string új rendezés irány
   */
   function reOrder(field,dir) {
	   document.forms.adminForm.filter_order.value = field;
	   document.forms.adminForm.filter_order_Dir.value = dir;
	   document.adminForm.limitstart.value = 0;
	   document.forms.adminForm.submit();
	   return false;
   }
  
