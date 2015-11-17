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

$installer->getConnection()->addColumn($this->getTable('mageplace_callforprice/callforprice'), 'customer_groups', 'varchar(100) NOT NULL');
$installer->getConnection()->addColumn($this->getTable('mageplace_callforprice/callforprice'), 'customer_ids', 'varchar(100) NOT NULL');

$installer->endSetup();