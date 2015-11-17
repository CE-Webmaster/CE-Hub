<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */

class Mageplace_Hideprice_Model_Option_Choose
{
        
    public function toOptionArray()
    {           
        $result = array();
        $groups = Mage::getModel('customer/group')->getCollection()->getData();
        foreach ($groups as $group){
            $result[] = array('value' => $group["customer_group_id"], 'label' => $group['customer_group_code']);
        }    
        return $result;
    }
}