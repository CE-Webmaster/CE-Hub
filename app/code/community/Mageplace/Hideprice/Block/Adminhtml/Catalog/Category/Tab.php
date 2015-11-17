<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


class Mageplace_Hideprice_Block_Adminhtml_Catalog_Category_Tab extends Mageplace_Hideprice_Block_Adminhtml_Catalog_Tab
{
	protected $_template = 'hideprice/catalog/category/tab.phtml';

	public function getCatalogObject()
	{
		return Mage::registry('current_category');
	}

	public function load($id, $storeId)
	{
		return $this->_getModel()->loadByCategoryId($id, $storeId);
	}

	public function getPriority()
	{
		$priority = $this->getHidepriceModel()->getPriority();
		return $priority ? $priority : 0;
	}
}