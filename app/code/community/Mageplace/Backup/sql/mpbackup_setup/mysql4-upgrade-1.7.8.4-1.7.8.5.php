<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */


/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('mpbackup/profile'), 'profile_check_memory_limit', 'tinyint(1) NOT NULL DEFAULT 0');

$installer->endSetup();