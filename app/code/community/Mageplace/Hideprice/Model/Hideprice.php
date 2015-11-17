<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */
class Mageplace_Hideprice_Model_Hideprice extends Mage_Core_Model_Abstract
{
	const MODE_HIDE_PRICE = 1;
	const MODE_SHOW_PRICE = 2;

	protected function _construct()
	{
		$this->_init('mageplace_hideprice/hideprice');
	}

	/**
	 * Load model by category id
	 * @param $categoryId
	 * @param null $storeId
	 * @return Mage_Core_Model_Abstract
	 */
	public function loadByCategoryId($categoryId, $storeId = null)
	{
		if (null === $storeId) {
			return $this->load($categoryId, Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_CATEGORY_ID);
		} else {
			$data = $this->getResource()->loadByStoreId($categoryId, Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_CATEGORY_ID, $storeId);
			if (!empty($data[$this->getResource()->getIdFieldName()])) {
				return $this->load($data[$this->getResource()->getIdFieldName()])->addData($data);
			} else {
				return $this;
			}
		}
	}

	/**
	 * Load model by product id
	 * @param $productId
	 * @param null $storeId
	 * @return Mage_Core_Model_Abstract
	 */
	public function loadByProductId($productId, $storeId = null)
	{
		if (null === $storeId) {
			return $this->load($productId, Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_PRODUCT_ID);
		} else {
			$data = $this->getResource()->loadByStoreId($productId, Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_PRODUCT_ID, $storeId);
			if (!empty($data[$this->getResource()->getIdFieldName()])) {
				return $this->load($data[$this->getResource()->getIdFieldName()])->addData($data);
			} else {
				return $this;
			}
		}
	}

	/**
	 * Is hideprice enabled
	 * @param $groupId
	 * @return bool|null
	 */
	public function isHideprice($groupId = null)
	{
		if (null === $groupId) {
			return $this->getMode() == self::MODE_HIDE_PRICE;
		}

		if ($this->getMode() != self::MODE_HIDE_PRICE) {
			return false;
		}

		$groups = $this->getData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_GROUPS);
		if ('' !== $groups) {
			$groups = explode(',', $groups);
			if (count($groups) > 0 && in_array($groupId, $groups)) {
				return true;
			}
		}

		return false;
	}

	public function isShowprice($groupId = null)
	{
		if (null === $groupId) {
			return $this->getMode() == self::MODE_SHOW_PRICE;
		}

		if ($this->getMode() != self::MODE_SHOW_PRICE) {
			return false;
		}

		$groups = explode(',', $this->getData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_GROUPS));
		if (count($groups) > 0 && in_array($groupId, $groups)) {
			return true;
		}

		return false;
	}

	public function checkGroup($groupId)
	{
		$groups = explode(',', $this->getData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_GROUPS));
		if (count($groups) > 0 && in_array($groupId, $groups)) {
			return true;
		}

		return false;
	}

	/**
	 * @return bool|null
	 */
	public function isExcludeChildren()
	{
		return (bool)$this->getData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_IS_EXCL_CHILDREN);
	}

	/**
	 * @return int
	 */
	public function getMode()
	{
		return (int)$this->getData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_HIDEPRICE_MODE);
	}

	/**
	 * @return int
	 */
	public function getPriority()
	{
		return (int)$this->getData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_PRIORITY);
	}

	/**
	 * @return Mage_Core_Model_Abstract
	 */
	protected function _beforeSave()
	{
		$isHide = $this->getData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_IS_HIDEPRICE);
		$this->setData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_IS_HIDEPRICE, self::boolToStr($isHide));
		return parent::_beforeSave();
	}

	/**
	 * @return $this|Mage_Core_Model_Abstract
	 */
	protected function _afterLoad()
	{
		$isHide = $this->getData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_IS_HIDEPRICE);
		$this->setData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_IS_HIDEPRICE, self::strToBool($isHide));
		return $this;
	}

	/**
	 * String to bool
	 * @param $str
	 * @return bool
	 */
	public static function strToBool($str)
	{
		return strpos($str, "on") > -1;
	}

	/**
	 * Bool to str
	 * @param $bool
	 * @return null|string
	 */
	public static function boolToStr($bool)
	{
		return $bool ? 'on' : null;
	}


	public function getCategoriesPriorities($storeId)
	{
		return $this->getResource()->getCategoriesPriorities($storeId);
	}
}