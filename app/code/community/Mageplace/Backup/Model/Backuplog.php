<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Model_Backuplog extends Mage_Core_Model_Abstract
{
	protected function _construct()
	{
		parent::_construct();

		$this->_init('mpbackup/backuplog');
	}
}