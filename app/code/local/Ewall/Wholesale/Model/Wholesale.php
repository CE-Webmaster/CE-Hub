<?php

class Ewall_Wholesale_Model_Wholesale extends Mage_Core_Model_Abstract
{
	//Wholesale model initialization
    public function _construct()
    {
        parent::_construct();
        $this->_init('wholesale/wholesale');
    }
}
