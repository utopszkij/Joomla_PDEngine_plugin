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
	 .controlItem {display:inline-block; width:auto; height:25px; border-style:solid; border-width:1px; border-radius:10px;
		  margin:5px; padding:5px}
	 .controlItem	 i {font-size:20px;} 
	 .filterForm {border-style:solid; border-width:1px; padding:5px;}
	  #jform_title {width:600px;}
	  #jform_parent_id {width:600px;}
	  #jform_json {width:600px;}
	  .invalid {background-color:#FFE0E0}
	 
	</style>
	
	<!--  confirm dialog -->
	<div id="confirmBox" style="position:fixed; left:200px; top:200px; width:auto; height:auto; padding:10px; background-color:#d0d0d0; display:none">
	  <center>
		<div class="message"></div>
		<button class="btn btn-yes yes" type="button">Igen</button>
		<button class="btn btn-no no" type="button">Nem</button>
	  </center>	
	</div>

	<!-- alertbox -->
	<div id="alertBox" style="position:fixed; left:200px; top:200px; width:auto; height:auto; padding:10px; background-color:#d0d0d0; display:none">
	  <center>
		<div class="message"></div>
		<button class="btn btn-yes yes" type="button">Rendben</button>
	  </center>	
	</div>
	<h2>PVOKS</h2>
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
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=configs">configs</a></div>
		  <div class="controlItem">
			<i class="icon-tree"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=categories">Categories</a></div>
		  <div class="controlItem">
			<i class="icon-users"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=members">Category members</a></div>
		  <div class="controlItem">
			<i class="icon-next"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=acrediteds">Acrediteds</a></div>
		  <div class="controlItem">
			<i class="icon-help"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=questions">Questions</a></div>
		  <div class="controlItem">
			<i class="icon-list"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=options">Options</a></div>
		  <div class="controlItem">
			<i class="icon-user"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=voters">Voters</a></div>
		  <div class="controlItem">
			<i class="icon-ok"></i>
			<a href="<?php echo JURI::base(); ?>index.php?option=com_pvoks&view=supports">Supports</a></div>
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
?>