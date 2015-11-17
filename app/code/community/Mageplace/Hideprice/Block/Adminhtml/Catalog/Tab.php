<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


abstract class Mageplace_Hideprice_Block_Adminhtml_Catalog_Tab
	extends Mage_Adminhtml_Block_Template
	implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	abstract public function getCatalogObject();

	abstract public function load($id, $storeId);

	/**
	 * Tab label
	 * @return string
	 */
	public function getTabLabel()
	{
		return $this->__('Hide Price');
	}

	/**
	 * Tab title
	 * @return string
	 */
	public function getTabTitle()
	{
		return $this->__('Hide price configuration');
	}

	/**
	 * Can show tab
	 * @return bool
	 */
	public function canShowTab()
	{
		return true;
	}

	/**
	 * Is tab hidden
	 * @return bool
	 */
	public function isHidden()
	{
		return false;
	}

	/**
	 * Is hideprice enabled in category
	 * @return bool
	 */
	public function getCurrentMode()
	{
		if ($this->getHidepriceModel()->getId()) {
			return $this->getHidepriceModel()->getMode();
		}

		return 0;
	}

	public function isExcludeChildren()
	{
		if ($this->getHidepriceModel()->getId()) {
			return $this->getHidepriceModel()->isExcludeChildren();
		}

		return false;
	}

	public function getEnabledGroups()
	{
		if ($this->getHidepriceModel()->getGroups() !== '') {
			return explode(',', $this->getHidepriceModel()->getGroups());
		}

		return array();
	}

	public function getModes()
	{
		return Mage::getSingleton('mageplace_hideprice/option_mode')->toOptionArray();
	}

	public function getGroups()
	{
		return Mage::getSingleton('mageplace_hideprice/option_choose')->toOptionArray();
	}

	/**
	 * @return Mageplace_Hideprice_Model_Hideprice
	 */
	protected function getHidepriceModel()
	{
		if (!$this->hasData('hideprice_model')) {
			$object = $this->getCatalogObject();
			$id     = $object->getId();

			if (!Mage::app()->isSingleStoreMode()) {
				$storeId = $object->getStoreId();
			} else {
				$storeId = 0;
			}

			$this->setData('hideprice_model', $this->load($id, $storeId));
		}

		return $this->_getData('hideprice_model');
	}

	/**
	 * @return Mageplace_Hideprice_Model_Hideprice
	 */
	protected function _getModel()
	{
		return Mage::getModel('mageplace_hideprice/hideprice');
	}
}