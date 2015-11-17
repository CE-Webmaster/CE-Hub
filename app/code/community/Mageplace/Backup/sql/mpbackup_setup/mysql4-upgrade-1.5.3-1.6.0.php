<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('mpbackup/profile'), 'profile_multiprocess_enable', 'tinyint(1) NOT NULL AFTER `profile_backup_path`');
$installer->getConnection()->addColumn($installer->getTable('mpbackup/profile'), 'profile_multiprocess_time', 'int(10) NOT NULL AFTER `profile_multiprocess_enable`');
$installer->getConnection()->addColumn($installer->getTable('mpbackup/profile'), 'profile_type', 'varchar(10) NOT NULL AFTER `profile_default`');


$installer->endSetup();