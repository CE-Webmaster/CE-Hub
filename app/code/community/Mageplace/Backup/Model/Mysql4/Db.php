<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */


if(Mage::helper('mpbackup/version')->isOld()) {
	class Mageplace_Backup_Model_Mysql4_Db extends Mage_Backup_Model_Mysql4_Db
	{
	}	
} else {
	class Mageplace_Backup_Model_Mysql4_Db extends Mage_Backup_Model_Resource_Db
	{
	}
}