<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */


/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$installer->run("ALTER TABLE `{$this->getTable('mpbackup/cron_schedule')}` CHANGE `schedule_id` `schedule_id` int(10) unsigned NULL DEFAULT NULL ");
$installer->endSetup();