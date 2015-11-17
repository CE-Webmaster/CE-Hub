<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Model_Mysql4_Temp_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	/**
	 * Constructor
	 */
	protected function _construct()
	{
		$this->_init('mpbackup/temp');
	}
}