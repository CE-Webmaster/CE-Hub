<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */
 
class Mageplace_Backup_Model_Observer
{
	function processControllerActionPredispatch($observer)
	{
		if((session_id() == 'mpbackup') && ($oldSessionId = Mage::helper('mpbackup')->getOldSessionId()) && ($oldSessionId != 'mpbackup')) {
			$module_name = Mage::app()->getRequest()->getModuleName();
			if($module_name != 'mpbackup') {
				session_write_close();				
				session_id($oldSessionId);
				@session_start();				
			}
		}
		
		
		return $this;
	}
	
	
}
