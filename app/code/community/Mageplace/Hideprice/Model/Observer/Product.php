<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


class Mageplace_Hideprice_Model_Observer_Product extends Mageplace_Hideprice_Model_Observer_Abstract
{
	public function getCatalogObject()
	{
		return Mage::registry('current_product');
	}

	public function load($id)
	{
		return $this->_getModel()->loadByProductId($id);
	}

	public function getIdFieldName()
	{
		return Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_PRODUCT_ID;
	}
}