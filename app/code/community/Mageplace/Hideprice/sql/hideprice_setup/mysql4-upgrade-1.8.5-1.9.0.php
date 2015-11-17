<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


$installer = $this;
$installer->startSetup();

$coreStoreTable = $installer->getTable('core/store');
$hpTable        = $installer->getTable('mageplace_hideprice/hideprice');
$hpStoreTable   = $installer->getTable('mageplace_hideprice/hideprice_store');

$installer->run("
CREATE TABLE IF NOT EXISTS `$hpStoreTable` (
	`hp_id`			int(10) NOT NULL,
	`store_id`		smallint(5) unsigned NOT NULL,
	`hp_mode`		tinyint(1) unsigned NOT NULL DEFAULT 0,
	`excl_children`	tinyint(1) unsigned NOT NULL DEFAULT 0,
	`groups`		varchar(255) NOT NULL,
	PRIMARY KEY (`hp_id`,`store_id`),
	CONSTRAINT `FK_HIDEPRICE_STORE_ID`
	  FOREIGN KEY (`hp_id`)
	  REFERENCES `$hpTable` (`id`)
	  ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_HIDEPRICE_STORE_STORE_ID`
      FOREIGN KEY (`store_id`)
      REFERENCES `$coreStoreTable` (`store_id`)
      ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;
");

$hpCollection = Mage::getModel('mageplace_hideprice/hideprice')->getCollection();

$data = array('store_id' => '0');
foreach ($hpCollection as $hpItem) {
	$data['hp_id'] = $hpItem->getId();
	if($hpItem->isHideprice()) {
		$data['hp_mode'] = 1;
	}
	$installer->getConnection()->insert($hpStoreTable, $data);
}

$installer->endSetup();