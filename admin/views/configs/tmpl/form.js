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
   
   /**
   * JSON editor képernyő kezelő objektum STATIC
   */
   JSONEditor = {
	  JSONstr : '',        
	  actionSpan : null,  
	  work : '',          
	  /** questionConfig screen to JSON feldolgozás into JSONstr
	  * @param JQuery_li
	  */
	  groupToJSON : function(group) {
		   var i = 0;
		   JSONEditor.JSONstr += '\n    {"group":"';
		   group.children('select').each(function(i,sel) {
			   JSONEditor.JSONstr += sel.options[sel.selectedIndex].value+'","actions":[';
		   });	   
		   group.children('span').each(function(i,actions) {
			  JSONEditor.JSONstr += actions.innerHTML+']'; 
		   });
		   JSONEditor.JSONstr += '}';
		   return;
	   },
	  /** questionConfig screen to JSON feldolgozás into JSONstr
      * @param JQuery_ul
      */
      groupsToJSON : function(groups) {
	    var i = 0;
	    JSONEditor.JSONstr += '\n  "groups":[';
	    groups.children('li').each(function(i,group) {
		    if (i == 0) {
			  JSONEditor.groupToJSON(jQuery(group));
			} else {
			  JSONEditor.JSONstr += ','; 
			  JSONEditor.groupToJSON(jQuery(group));
			}  
	    });
	    JSONEditor.JSONstr += '\n  ]';
	    return;
      },
	  /** questionConfig screen to JSON feldolgozás into JSONstr
	  * @param JQuery_li
	  */
	  stepToJSON : function(step) {
		   var i = 0;
		   step.children('input').each(function(i,item) {
				JSONEditor.JSONstr += '\n{"title":"'+item.value+'",';
		   });
		   step.children('ul').each(function(i,grops) {
				JSONEditor.groupsToJSON(jQuery(grops));
		   });
		   JSONEditor.JSONstr += '\n}';
		   return;
	   },
	   /** questionConfig screen to JSON feldolgozás into JSONstr
       * @param JQuery_ul
       */
       stepsToJSON : function(steps) {
		   var i = 0;
		   JSONEditor.JSONstr = '{"steps":[';
		   steps.children('li').each(function(i,step) {
			   if (i == 0) {
				   JSONEditor.stepToJSON(jQuery(step));
			   } else {
				   JSONEditor.JSONstr += ','; 
				   JSONEditor.stepToJSON(jQuery(step));
			   }
		   });
		   return JSONEditor.JSONstr+'\n]}';
       },
	   /**
	   * init jquestion config scr step from array
	   * @param JQuery_li
	   * @param array   
	   */
	   initActions : function(li, init) {
		   var s = '';
		   var i = 0;
		   for (i=0; i<init.length; i++) {
			   if (i==0) {
				   s = '"'+init[i]+'"';
			   } else {
				   s += ',"'+init[i]+'"';
			   }
		   }
		   li.find('span').html(s);
	   },
	   /**
	   * init jquestion config scr step from object
	   * @param JQuery_li
	   * @param {"title":"xxx", "groups":[{"group":"xxx","actions:["xxx",...]},...]}   
	   */
	   initStep : function(li, init) {
		  var i = 0;  
		  var w = null;
		  li.find('input').val(init.title);	   
		  for (i=0; i < init.groups.length; i++) {
			li.find('ul').append(jQuery('#group0').html());
			w = li.find('ul').find('li').last();
			w.find('select').val(init.groups[i].group);
			JSONEditor.initActions(w, init.groups[i].actions)		
		  }
	    },
		/**
		* a config képernyöről áttölt a textare -back
		*/
		scrToTXT : function() {
				var s = JSONEditor.stepsToJSON(jQuery('#ulSteps'));
				jQuery('#jform_json').val(s);
		},
		/**
		* akció gombok újradefiniálása
		*/
	   	redefButtons : function() {
			jQuery('.btnAddGroup').click(function(event) {
					jQuery(event.target).parent().children('ul').append(jQuery('#group0').html());
					jQuery('.btnDelGroup').click(function(event) {
						jQuery(event.target).parent().remove();
						JSONEditor.scrToTXT();
					});
					jQuery('.btnEditActions').click(function(event) {
						JSONEditor.actionSpan = jQuery(event.target).parent().children('span');
						// init actionsPopup scr
						jQuery('#actionsPopup').children('input').each(function (i,item) {
							item.checked = (JSONEditor.actionSpan.html().indexOf('"'+item.value+'"') >= 0); 
						});
						jQuery('#actionsPopup').show();
						JSONEditor.scrToTXT();
					});
			});
			jQuery('.btnDelStep').click(function(event) {
				jQuery(event.target).parent().remove();
				JSONEditor.scrToTXT();
			});
			jQuery('.btnDelGroup').click(function(event) {
				jQuery(event.target).parent().remove();
				JSONEditor.scrToTXT();
			});
			jQuery('.btnEditActions').click(function(event) {
				JSONEditor.actionSpan = jQuery(event.target).parent().children('span');
				// init actionsPopup scr
				jQuery('#actionsPopup').children('input').each(function (i,item) {
					item.checked = (JSONEditor.actionSpan.html().indexOf('"'+item.value+'"') >= 0); 
				});
				jQuery('#actionsPopup').show();
				JSONEditor.scrToTXT();
			});
		}
   };
   
   
   jQuery(function () {
		   // form onActivate
			jQuery('#jform_title').focus();
		    jQuery.actionSpan = '';
			jQuery.work = '';
		   
			/** 
			* jquestionConfig scr actionsPopup OK button function
			*/
			jQuery('#actionsPopupOK').click(function(event) {
				JSONEditor.work = '';
				jQuery('#actionsPopup').find('input').each(function (i,item) {
					if (item.checked) {
						if (JSONEditor.work == '') {
							JSONEditor.work += '"'+item.value+'"';
						} else {
							JSONEditor.work += ',"'+item.value+'"';
						}
					}
				}); 
				JSONEditor.actionSpan.html(JSONEditor.work);
				jQuery('#actionsPopup').hide();
				JSONEditor.scrToTXT();
			});

			
			/**
			* jquestionConfig scr stepsOK button function
			*/
			jQuery('#stepsOK').click(function(event) {
				var s = JSONEditor.stepsToJSON(jQuery('#ulSteps'));
				jQuery('#jform_json').val(s);
			});

			/**
			* jquestionConfig scr addstep button function
			*/
			jQuery('#addStep').click(function( event) {
				jQuery('#ulSteps').append(jQuery('#step0').html());
				JSONEditor.redefButtons();
				JSONEditor.scrToTXT();
			});
			
			jQuery('#ulSteps').sortable();

			// init questionConfig scr
			var init = JSON.parse(jQuery('#jform_json').val().replace('\n',''));
			if (init) {
				var i = 0;
				var w = null;
				if (init.steps) {
					for (i=0; i < init.steps.length; i++) {
						jQuery('#ulSteps').append(jQuery('#step0').html());
						w = jQuery('#ulSteps').find('li').last();
						JSONEditor.initStep(w, init.steps[i]);
					}
				}
				JSONEditor.redefButtons();
				JSONEditor.scrToTXT();
			}
			
			// init actionsPopup
			jQuery('#actionsPopup').find('div').each(function(i,item) {
				jQuery(item).append('<var>'+jQuery(item).find('input').val()+'</var>');
				
			});
	});
