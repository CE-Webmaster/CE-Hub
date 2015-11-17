<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */

$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($this->getTable('mageplace_hideprice/hideprice_store'), Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_PRIORITY, 'int(10) default "0"');
$installer->endSetup();