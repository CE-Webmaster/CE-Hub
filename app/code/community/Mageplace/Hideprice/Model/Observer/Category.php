<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


class Mageplace_Hideprice_Model_Observer_Category extends Mageplace_Hideprice_Model_Observer_Abstract
{
	/**
	 * Add tab to category edit menu
	 * @param $observer
	 */
	public function addTab(Varien_Event_Observer $observer)
	{
		if (Mage::helper('hideprice')->getStoreConfig('options/category_enable')) {
			$tab = $observer->getEvent()->getTabs();

			$tab->addTab('hideprice', array(
				'label'   => Mage::helper('catalog')->__('Hide Price'),
				'content' => $tab->getLayout()->createBlock('mageplace_hideprice/adminhtml_catalog_category_tab')->toHtml()
			));
		}
	}

	public function getCatalogObject()
	{
		return Mage::registry('current_category');
	}

	public function load($id)
	{
		return $this->_getModel()->loadByCategoryId($id);
	}

	public function getIdFieldName()
	{
		return Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_CATEGORY_ID;
	}
}