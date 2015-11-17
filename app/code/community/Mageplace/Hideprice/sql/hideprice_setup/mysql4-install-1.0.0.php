<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */

$installer = $this;
$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS `{$this->getTable('mageplace_hideprice/hideprice')}` (        
    `id`         int(10) NOT NULL AUTO_INCREMENT,
    `id_cat`     int(10) unsigned,
    `hideprice`  varchar(2),
    PRIMARY KEY (`id`),	
    CONSTRAINT `FK_CALLFORPRICE_HIDEPRICE_ID_CAT` FOREIGN KEY (`id_cat`) REFERENCES `{$this->getTable('catalog/category')}` (`entity_id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;");
$installer->endSetup();
