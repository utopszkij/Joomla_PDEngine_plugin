<?php
/**
 * pvoks component configs view viewer
 * @author		Fogler Tibor
 * @copyright	nincs
 * @license		GNU/GPL
 */

defined("_JEXEC") or die("Restricted access");

class PvoksViewOptions extends JViewLegacy {
	protected $items;
	protected $pagination;
	protected $state;
	public function display($tpl = null){
		parent::display($tpl);
	}
}
?>