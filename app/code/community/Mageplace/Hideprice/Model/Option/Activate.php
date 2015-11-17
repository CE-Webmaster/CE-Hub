<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */

class Mageplace_Hideprice_Model_Option_Activate
{
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => Mage::helper('hideprice')->__('Yes')),
            array('value' => 2, 'label' => Mage::helper('hideprice')->__('No'))
        );
    }
}