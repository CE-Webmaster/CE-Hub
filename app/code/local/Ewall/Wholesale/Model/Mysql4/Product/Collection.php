<?php

class Ewall_Wholesale_Model_Mysql4_Product_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	//initialization resource collection for wholesale procuts model
    public function _construct()
    {
        parent::_construct();
        $this->_init('wholesale/product');
    }
}
