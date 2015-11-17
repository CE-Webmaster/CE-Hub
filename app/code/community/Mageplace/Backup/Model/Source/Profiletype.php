<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Model_Source_Profiletype extends Mageplace_Backup_Model_Source_Abstract
{
	public function toOptionArray()
	{
		return array(
			array('value' => Mageplace_Backup_Model_Profile::TYPE_DBFILES, 'label' => $this->_getHelper()->__('DB and Files')),
			array('value' => Mageplace_Backup_Model_Profile::TYPE_DB, 'label' => $this->_getHelper()->__('DB')),
			array('value' => Mageplace_Backup_Model_Profile::TYPE_FILES, 'label' => $this->_getHelper()->__('Files')),
		);
	}
}
