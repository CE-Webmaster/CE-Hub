<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('massredirect')};
CREATE TABLE {$this->getTable('massredirect')} (
    `redirect_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `website_id` int(11) NOT NULL default '0',
    `old_url` varchar(255) NOT NULL default '',
    `new_url` varchar(255) default '',
    `sku` varchar(255) default '',
    PRIMARY KEY(`redirect_id`),
    KEY `website_id` (`website_id`),
    KEY `old_url` (`old_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Mapping of old site URLs to Magento URLs.'

");

$installer->endSetup();
