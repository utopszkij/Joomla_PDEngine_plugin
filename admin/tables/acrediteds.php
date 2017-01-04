 <?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class PvoksTableAcrediteds extends JTable {
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	public function __construct(& $db) {
		parent::__construct('#__pvoks_acrediteds', 'id', $db);
	}

	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	public function bind($array, $ignore = '') 	{ 
		return parent::bind($array, $ignore);		
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	public function check()	{
		/** check for valid name */
		/**
		if (trim($this->name) == '') {
			$this->setError(JText::_('Your Field must contain a name.')); 
			return false;
		}
		**/		
		return true;
	}
}
 