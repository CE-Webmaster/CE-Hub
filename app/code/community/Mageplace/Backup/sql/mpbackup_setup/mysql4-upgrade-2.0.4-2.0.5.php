<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */


/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$profileTable = $installer->getTable('mpbackup/profile');

$installer->startSetup();

$installer->getConnection()->addColumn(
    $profileTable,
    Mageplace_Backup_Model_Profile::COLUMN_AUTH_ENABLE,
    'tinyint(1) NOT NULL DEFAULT "0"'
);

$installer->endSetup();