<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('mageplace_hideprice/hideprice'), 'id_prod', 'INT(10)');

$installer->endSetup();