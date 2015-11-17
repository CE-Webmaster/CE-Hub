<?php

class Ewall_Wholesale_Model_Product extends Mage_Core_Model_Abstract
{
	//Wholesale price for products model initialization
    public function _construct()
    {
        parent::_construct();
        $this->_init('wholesale/product');
    }
}
