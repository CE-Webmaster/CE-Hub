<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


class Mageplace_Hideprice_Block_Adminhtml_Catalog_Product_Tab extends Mageplace_Hideprice_Block_Adminhtml_Catalog_Tab
{
	protected $_template = 'hideprice/catalog/product/tab.phtml';

	public function getCatalogObject()
	{
		return Mage::registry('current_product');
	}

	public function load($id, $storeId)
	{
		return $this->_getModel()->loadByProductId($id, $storeId);
	}
}