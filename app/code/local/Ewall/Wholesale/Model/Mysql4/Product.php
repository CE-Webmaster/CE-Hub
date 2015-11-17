<?php

class Ewall_Wholesale_Model_Mysql4_Product extends Mage_Core_Model_Mysql4_Abstract
{
	//initialization resource for wholesale products model
    public function _construct()
    {    
        // Note that the wholesale_id refers to the key field in your database table.
        $this->_init('wholesale/product', 'product_rule_id');
    }
}
