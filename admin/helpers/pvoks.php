<?php
/**
  * com_pvoks
  * Licence: GNU/GPL
  * Author: Fogler Tibor   
  * Author-email: tibor.fogler@gmail.com
  * Author-web: github.com/utopszkij
  * Verzió: V1.00 
  */

class PvoksHelper {
  public function echoPvoksHeader() { 	
    ?>
	<style type="text/css">
	 .hibaUzenet, .error {background-color:#FFE0E0; padding:10px;}
	 .infoUzenet {background-color:#E0FFE0; padding:10px;}
	 .controlItem {display:inline-block; width:auto; height:auto; border-style:solid; border-width:1px; border-radius:10px;
		  margin:2px; padding:5px}
	 .controlItem	 i {font-size:20px;} 
	 .filterForm {border-style:solid; border-width:1px; padding:5px;}
	 .filterForm label {display:inline-block; width:100px;}
	 .filterForm input {margin-bottom:0px;}
	 .filterForm select {margin-bottom:0px;}
	 .filterForm .control-group {display:inline-block; width:auto;}
	 .filterForm .control-buttons {float:right; display:inline-block; width:auto;}
	  #jform_title {width:600px;}
	  #jform_parent_id {width:600px;}
	  #jform_json {width:600px;}
	  .invalid {background-color:#FFE0E0}
	 
	</style>
	
	<!--  confirm dialog -->
	<div id="confirmBox" style="position:fixed; left:200px; top:200px; width:auto; height:auto; padding:10px; background-color:#d0d0d0; display:none">
	  <center>
		<div class="message"></div>
		<button class="btn btn-yes yes" type="button"><?php echo JText::_('PVOKS_YES'); ?></button>
		<button class="btn btn-no no" type="button"><?php echo JText::_('PVOKS_NO'); ?></button>
	  </center>	
	</div>

	<!-- alertbox -->
	<div id="alertBox" style="position:fixed; left:200px; top:200px; width:auto; height:auto; padding:10px; background-color:#d0d0d0; display:none">
	  <center>
		<div class="message"></div>
		<button class="btn btn-yes yes" type="button">Rendben</button>
	  </center>	
	</div>
	<h2><?php echo JText::_('PVOKS'); ?></h2>
	<?php
  }	
	/**
	  * táblázat fejlécnek kiirása
	  * @param string th felirat
	  * @param string th fieldname
	  * @param string jelenlegi rendezés field
	  * @param string jelenlegi rendezés irány
	*/
	function echoColHeader($label, $field, $listDirn, $listOrder) {
		$s = '&nbsp;&nbsp;&nbsp;';
		$newDirn = 'asc';
		$c = '';
		if ($listOrder == $field) {
			$c = ' ordering';
			if (($listDirn == 'asc') | ($listDirn == '')) {
				$s = '<i class="icon-arrow-down-3"></i>';
				$newDirn = 'desc';
			} else {
				$s = '<i class="icon-arrow-up-3"></i>';
				$newDirn = 'asc';
			}	
		}
		?>
		<span onclick="reOrder('<?php echo $field; ?>','<?php echo $newDirn; ?>')" 
			  style="cursor:pointer" class="grid-header<?php echo $c; ?>"><?php echo JText::_($label).$s; ?></span>
		<?php
	}
	
	public function echoControlPanel() {
		?>
		<div style="clear:both"></div>
		<div class="controlpanel">
		  <div class="controlItem">
			<i class="icon-tools"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=configs"><?php echo JText::_('PVOKS_CONFIGS_LIST'); ?></a></div>
		  <div class="controlItem">
			<i class="icon-tree"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=categories"><?php echo JText::_('PVOKS_CATEGORIES_LIST'); ?></a></div>
		  <div class="controlItem">
			<i class="icon-users"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=members"><?php echo JText::_('PVOKS_MEMBERS_LIST'); ?></a></div>
		  <div class="controlItem">
			<i class="icon-next"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=acrediteds"><?php echo JText::_('PVOKS_ACREDITEDS_LIST'); ?></a></div>
		  <div class="controlItem">
			<i class="icon-help"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=questions"><?php echo JText::_('PVOKS_QUESTIONS_LIST'); ?></a></div>
		  <div class="controlItem">
			<i class="icon-list"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=options"><?php echo JText::_('PVOKS_OPTIONS_LIST'); ?></a></div>
		  <div class="controlItem">
			<i class="icon-user"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=voters"><?php echo JText::_('PVOKS_VOTERS_LIST'); ?></a></div>
		</div>	
		<div style="clear:both"></div>
		<?php
    }
	
	/**
	* echo select options recursive function
	* @param integer parent_id
	* @param string margin
	* @param integer actualValue
	*/
	function echoCategoryOptions($parent, $s, $actValue) {
		$db = JFactory::getDBO();
		$db->setQuery('select id, title from #__pvoks_categories where parent_id = '.$db->quote($parent).' order by 2');
		$res = $db->loadObjectList();
		if (is_array($res)) {
			foreach ($res as $res1) {
				if ($res1->id == $actValue) 
					echo '<option value="'.$res1->id.'" selected="selected">'.$s.$res1->title.'</option>
					';
				else
					echo '<option value="'.$res1->id.'">'.$s.$res1->title.'</option>
					';
				$this->echoCategoryOptions($res1->id, $s.'-&nbsp;', $actValue);	
			}
		}
	}
	
	/** img tag kiemelése szövegbõl
	* @param string (html code)
	* @return string 'src="....."' vagy ''
	*/
	public function getImgSrc(&$text, $clear = false) {
		$matches = Array();
		$src = '';
		preg_match('/<img[^>]+>/i', $text, $matches);
		if (count($matches) > 0) {
			  $img = $matches[0];
			  // src attributum kiemelése
			  preg_match('/src="[^"]+"/i', $img, $matches);
			  if (count($matches) > 0) {
				$src = $matches[0];
			  } else {
				$src = '';  
			  }	
		} else {
			  $src = '';	
		}
		if ($clear)
		   $text = str_replace('<img','<iimg', $text);
	   return $src;
	}

	
}
?>