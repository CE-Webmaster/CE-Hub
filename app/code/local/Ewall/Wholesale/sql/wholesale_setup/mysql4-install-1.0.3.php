<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('wholesale')};
CREATE TABLE {$this->getTable('wholesale')} (
  `wholesale_id` int(11) unsigned NOT NULL auto_increment,
  `rulename` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `websiteid` smallint(5) NOT NULL default '0',
  `storeid` smallint(5) NOT NULL default '0',
  `customer_group_id` varchar(255) NULL,
  `discount` decimal(12,4) NOT NULL default '0.0000',
  `priority` smallint(5) NOT NULL default '0',
  `category_ids` varchar(255) NULL,
  `products` varchar(255) NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  `categories` varchar(255) NOT NULL default '',
  PRIMARY KEY (`wholesale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('wholesale_products')};
CREATE TABLE {$this->getTable('wholesale_products')} (
  `product_rule_id` int(11) unsigned NOT NULL auto_increment,
  `rule_id` int(11) NOT NULL default '0',
  `product_id` int(11) NOT NULL default '0',
  `customer_group_id` smallint(6) NOT NULL default '0',
  `websiteid` smallint(5) NOT NULL default '0',
  `storeid` smallint(5) NOT NULL default '0',
  `rule_price` decimal(12,4) NOT NULL default '0.0000',
  PRIMARY KEY (`product_rule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");
    
$installer->endSetup(); 
