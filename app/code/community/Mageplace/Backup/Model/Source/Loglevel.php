<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Model_Source_Loglevel extends Mageplace_Backup_Model_Source_Abstract
{

	public function toOptionArray()
	{
		return array(
			array('value' => Mageplace_Backup_Model_Backup::LOG_LEVEL_ALL, 'label' => $this->_getHelper()->__('All')),
			array('value' => Mageplace_Backup_Model_Backup::LOG_LEVEL_INFO, 'label' => $this->_getHelper()->__('Info')),
			array('value' => Mageplace_Backup_Model_Backup::LOG_LEVEL_WARNING, 'label' => $this->_getHelper()->__('Warnings & Errors')),
			array('value' => Mageplace_Backup_Model_Backup::LOG_LEVEL_OFF, 'label' => $this->_getHelper()->__('Off')),
		);
	}

	public function cronOptionArray()
	{
		return array(
			array('value' => Mageplace_Backup_Model_Backup::LOG_LEVEL_ALL, 'label' => $this->_getHelper()->__('All')),
			array('value' => Mageplace_Backup_Model_Backup::LOG_LEVEL_INFO, 'label' => $this->_getHelper()->__('Info')),
			array('value' => Mageplace_Backup_Model_Backup::LOG_LEVEL_OFF, 'label' => $this->_getHelper()->__('Off')),
		);
	}
}
