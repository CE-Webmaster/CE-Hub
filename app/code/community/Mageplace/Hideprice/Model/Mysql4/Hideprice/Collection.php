<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */

class Mageplace_Hideprice_Model_Mysql4_Hideprice_Collection 
    extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    
    protected function _construct() {
        $this->_init('mageplace_hideprice/hideprice');
    }

}