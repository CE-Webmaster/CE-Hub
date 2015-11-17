<?php
/**
* Mageplace Callforprice
*
* @category      Mageplace
* @package       Mageplace_Callforprice
* @copyright     Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
* @license       http://www.mageplace.com/disclaimer.html
*/

$installer = $this;
$installer->startSetup();

$installer->run("
    CREATE TABLE IF NOT EXISTS `{$this->getTable('mageplace_callforprice/callforprice')}` (
    `id`            int(10) NOT NULL AUTO_INCREMENT,
    `id_prod`       int(10) unsigned,
    `id_cat`        int(10) unsigned,
    `callforprice`  varchar(2),
    PRIMARY KEY (`id`),
	CONSTRAINT `FK_CALLFORPRICE_CALLFORPRICE_ID_PROD` FOREIGN KEY (`id_prod`) REFERENCES `{$this->getTable('catalog/product')}` (`entity_id`) ON DELETE CASCADE,
	CONSTRAINT `FK_CALLFORPRICE_CALLFORPRICE_ID_CAT` FOREIGN KEY (`id_cat`) REFERENCES `{$this->getTable('catalog/category')}` (`entity_id`) ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=UTF8;");
$installer->endSetup();
