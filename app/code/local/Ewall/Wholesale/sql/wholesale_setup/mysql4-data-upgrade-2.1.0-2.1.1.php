<!--Upgrage from V2.1.0 - V2.1.1-->
<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE `{$this->getTable('wholesale')}` DROP COLUMN `categories`;
ALTER TABLE `{$this->getTable('wholesale_products')}` DROP COLUMN `customer_group_id`;
ALTER TABLE `{$this->getTable('wholesale_products')}` ADD `customer_group_id` VARCHAR( 255 ) NOT NULL default '';

");

$installer->endSetup(); 
