<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */

class Mageplace_Hideprice_Model_Option_Choosestore
{
        
    public function toOptionArray()
    {           
        $result = array();
        $stores = Mage::app()->getStores();
		$used_stores = array();
		$result[] = array('value' => ' ', 'label' => ' ');
		foreach($stores as $store){
			if(!in_array($store->getGroup()->getId(), $used_stores)){
				$result[] = array('value' => $store->getGroup()->getId(), 'label' => $store->getGroup()->getName());
				array_push($used_stores, $store->getGroup()->getId());
			}	
		}        
        return $result;
    }
}