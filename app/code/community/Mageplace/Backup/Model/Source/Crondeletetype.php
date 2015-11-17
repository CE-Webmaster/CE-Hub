<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Model_Source_Crondeletetype extends Mageplace_Backup_Model_Source_Abstract
{

	public function toOptionArray()
	{
		return array(
			array('value' => '', 'label' => $this->_getHelper()->__('Disable')),
			array('value' => Mageplace_Backup_Model_Profile::CRON_DELETE_TYPE_ROTATION, 'label' => $this->_getHelper()->__('Rotation')),
			array('value' => Mageplace_Backup_Model_Profile::CRON_DELETE_TYPE_DELETE_OLD, 'label' => $this->_getHelper()->__('Delete old')),
		);
	}
}
