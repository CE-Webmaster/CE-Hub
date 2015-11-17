<!--Upgrage from V2.1.1 - V2.1.2-->
<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE `{$this->getTable('wholesale')}` DROP COLUMN `category_ids`;
ALTER TABLE `{$this->getTable('wholesale')}` ADD `category_ids` TEXT NOT NULL default '';
ALTER TABLE `{$this->getTable('wholesale')}` DROP COLUMN `products`;
ALTER TABLE `{$this->getTable('wholesale')}` ADD `products` TEXT NOT NULL default '';

");

$installer->endSetup(); 
