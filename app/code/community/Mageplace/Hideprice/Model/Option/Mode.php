<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */
class Mageplace_Hideprice_Model_Option_Mode
{

	public function toOptionArray()
	{
		$result   = array();
		$result[] = array('value' => 0, 'label' => Mage::helper('hideprice')->__('Disable'));
		$result[] = array('value' => Mageplace_Hideprice_Model_Hideprice::MODE_HIDE_PRICE, 'label' => Mage::helper('hideprice')->__('Hide Price'));
		$result[] = array('value' => Mageplace_Hideprice_Model_Hideprice::MODE_SHOW_PRICE, 'label' => Mage::helper('hideprice')->__('Show Price'));
		return $result;
	}
}