<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */
class Mageplace_Hideprice_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_MODULE_PREFIX = 'hide_price';

    const XML_PATH_ADVANCED_PROCESSOR  = 'advanced/processor';
    const XML_PATH_PREFIX_CSS_SELECTOR = 'selectors/';
    const XML_PATH_PREFIX_FILTER       = 'filters/';
    const XML_PATH_OTHER_BLOCKS        = 'blocks/types';

    public static $storeId;
    public static $groupId;
    public static $categoryEnable;
    public static $productEnable;
    public static $higherPriorityCategories;

    protected static $_isCustomerGroup;
    protected static $_isEnabled;
    protected static $_flagGroup;

    static private $categories = array();

    public function __construct()
    {
        if (null === self::$storeId) {
            if (!Mage::app()->isSingleStoreMode()) {
                self::$storeId = Mage::app()->getStore()->getStoreId();
            } else {
                self::$storeId = null;
            }
            self::$groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        }
    }

    /**
     * Return name of installed processor
     * @return string
     */
    public function getProcessorName()
    {
        return $this->getStoreConfig(self::XML_PATH_ADVANCED_PROCESSOR);
    }

    /**
     * Return css selector by name in config
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getCssSelector($name)
    {
        $xmlPath = self::XML_PATH_PREFIX_CSS_SELECTOR . $name;

        return $this->getStoreConfig($xmlPath);
    }

    /**
     * Is filter enable flag
     *
     * @param string $name name of filter
     *
     * @return bool
     */
    public function isFilterEnable($name)
    {
        $xmlPath = self::XML_PATH_PREFIX_FILTER . $name;

        return (bool)$this->getStoreConfig($xmlPath);
    }

    /**
     * Return module config
     *
     * @param string $path without module prefix
     *
     * @return mixed
     */
    public function getStoreConfig($path)
    {
        $xmlPath = self::XML_PATH_MODULE_PREFIX . '/' . $path;

        return Mage::getStoreConfig($xmlPath);
    }

    /**
     * Return prepared message
     *
     * @param $message
     *
     * @return string
     */
    public function prepareReplacement($message)
    {
        $customerLoginHref = $this->getStoreConfig('options/customer_login_href');
        if ($customerLoginHref) {
            $href = Mage::helper('customer')->getLoginUrl();
        } else {
            $href = $this->getStoreConfig('options/href');
        }
        if (strlen($href)) {
            $href = "href='" . $href . "'";
        }
        $replacement = '<div class="hideprice"><a ' . $href . '>' . $message . '</a></div>';

        return $replacement;
    }

    /**
     * Return blocks available for processing and hide prices
     * @return array
     */
    public function getProcessingBlocks()
    {
        static $resultBlocks = array();

        if (empty($resultBlocks)) {
            $blocks = explode(';', $this->getStoreConfig(self::XML_PATH_OTHER_BLOCKS));

            $resultBlocks = array();
            foreach ($blocks as $blockName) {
                $blockName = trim($blockName);
                if (!empty($blockName)) {
                    $resultBlocks[] = $blockName;
                }
            }
        }

        return $resultBlocks;
    }

    public function isHide($product)
    {
        if (!$product instanceof Mage_Catalog_Model_Product) {
            if (!is_object($product) || !($productId = $product->getId())) {
                return false;
            }

            $product = Mage::getModel('catalog/product')->load($productId);
            if (!$productId = $product->getId()) {
                return false;
            }
        }

        if (null === self::$_isEnabled) {
            if (Mage::app()->getStore()->isAdmin()) {
                self::$_isEnabled = false;
            } else {
                $storeId = Mage::app()->getStore()->getGroup()->getId();
                $stores  = $this->getStoreConfig('options/choose_stores');
                $stores  = explode(',', $stores);
                if (in_array($storeId, $stores)) {
                    self::$_isEnabled = false;
                } else {
                    self::$_isEnabled = true;
                }
            }
        }

        if (false === self::$_isEnabled) {
            return false;
        }

        if (null === self::$_isCustomerGroup) {
            self::$_isCustomerGroup = (int)$this->getStoreConfig('options/customer_group_enable');
            self::$categoryEnable   = (bool)$this->getStoreConfig('options/category_enable');
            self::$productEnable    = (bool)$this->getStoreConfig('options/product_enable');
        }

        if (1 == self::$_isCustomerGroup || self::$categoryEnable || self::$productEnable) {
            if (null === self::$_flagGroup) {
                $groupsString = $this->getStoreConfig('options/choose_customer_groups');

                if ($groupsString != '') {
                    $groupsArray = explode(",", $groupsString);
                } else {
                    $groupsArray = array();
                }

                self::$_flagGroup = false;

                foreach ($groupsArray as $gr) {
                    $gr = trim($gr);
                    if (self::$groupId == $gr) {
                        self::$_flagGroup = true;
                        break;
                    }
                }
            }

            if (self::$_flagGroup || self::$categoryEnable || self::$productEnable) {
                if (self::$productEnable) {
                    /** @var $hideModel Mageplace_Hideprice_Model_Hideprice */
                    $hideModel = Mage::getModel('mageplace_hideprice/hideprice')
                        ->loadByProductId($product->getId(), array(0, self::$storeId));
                }

                if (self::$productEnable && $hideModel->getId() && $hideModel->isShowprice(self::$groupId)) {
                    $skip = true;
                } elseif (self::$productEnable && $hideModel->getId() && $hideModel->isHideprice(self::$groupId)) {
                    return $this->getStoreConfig('options/text_message_product');
                } elseif (self::$categoryEnable) {
                    if (($categoriesIds = $product->getCategoryIds()) && is_array($categoriesIds)) {
                        $categories = Mageplace_Hideprice_Model_Observer::categoriesPriorities();

                        $intersectCategoriesIds = array_intersect($categories, $categoriesIds);
                        if (!empty($intersectCategoriesIds)) {
                            foreach ($intersectCategoriesIds as $categoryId) {
                                $category = Mage::getModel('catalog/category')->load($categoryId);
                                $check    = $this->checkCategory($category);
                                if ($check->getHide()) {
                                    return $this->getStoreConfig('options/text_message_category');
                                }
                                $skip = $check->getSkip();
                            }
                        } else {
                            if (($category = $product->getCategory())
                                && is_object($category)
                                && ($categoryId = $category->getId())
                                && in_array($categoryId, $categoriesIds)
                            ) {
                                $check = $this->checkCategory($category);
                                if ($check->getHide()) {
                                    return $this->getStoreConfig('options/text_message_category');
                                }
                                $skip = $check->getSkip();
                            } else {
                                foreach ($categoriesIds as $categoryId) {
                                    $category = Mage::getModel('catalog/category')->load($categoryId);
                                    $check    = $this->checkCategory($category);
                                    if ($check->getHide()) {
                                        return $this->getStoreConfig('options/text_message_category');
                                    }
                                    $skip = $check->getSkip();
                                }
                            }
                        }
                    }
                }

                if (empty($skip) && self::isNeedToBeHidden()) {
                    return $this->getStoreConfig('options/text_message_product');
                }
            } else {
                return $this->getStoreConfig('options/text_message');
            }

        } else {
            return $this->getStoreConfig('options/text_message');
        }

        return false;
    }

    public function checkCategory($category)
    {
        if (!$category) {
            if (!$category = Mage::registry('current_category')) {
                return false;
            }
        }

        if (!$category instanceof Mage_Catalog_Model_Category) {
            $category = Mage::getModel('catalog/category')->load(intval($category));
        }

        $hide = false;
        $skip = false;

        if ($categoryId = $category->getId()) {
            if (array_key_exists($categoryId, self::$categories)) {
                $hide = self::$categories[$categoryId]['hide'];
                $skip = self::$categories[$categoryId]['skip'];
            } else {
                if (($path = $category->getPathIds()) && is_array($path)) {
                    $curCategory = array_pop($path);

                    /** @var $hideModel Mageplace_Hideprice_Model_Hideprice */
                    $hideModel = Mage::getModel('mageplace_hideprice/hideprice')
                        ->loadByCategoryId($curCategory, array(0, self::$storeId));

                    if ($hideModel->getId() && $hideModel->getMode() > 0) {
                        if ($hideModel->isShowprice()) {
                            if ($hideModel->checkGroup(self::$groupId)) {
                                $hide = false;
                            } else {
                                $hide = true;
                            }
                        } else {
                            if ($hideModel->checkGroup(self::$groupId)) {
                                $hide = true;
                            } else {
                                $hide = false;
                            }
                        }

                        $skip = true;
                    } else {
                        foreach ($path as $catId) {
                            /** @var $hideModel Mageplace_Hideprice_Model_Hideprice */
                            $hideModel = Mage::getModel('mageplace_hideprice/hideprice')
                                ->loadByCategoryId($catId, array(0, self::$storeId));

                            if ($hideModel->getId() && $hideModel->getMode() > 0) {
                                if (!$hideModel->isExcludeChildren()) {
                                    if ($hideModel->isShowprice()) {
                                        if ($hideModel->checkGroup(self::$groupId)) {
                                            $hide = false;
                                        } else {
                                            $hide = true;
                                        }
                                    } else {
                                        if ($hideModel->checkGroup(self::$groupId)) {
                                            $hide = true;
                                        } else {
                                            $hide = false;
                                        }
                                    }

                                    $skip = true;
                                }
                            }
                        }
                    }
                }

                self::$categories[$categoryId] = array('hide' => $hide, 'skip' => $skip);

            }
        }

//		var_dump($categoryId, $hide, $skip, @$path, $positions);

        return new Varien_Object(array(
            'hide' => $hide,
            'skip' => $skip
        ));
    }

    public static function categoriesPriorities()
    {
        if (null === self::$higherPriorityCategories) {
            self::$higherPriorityCategories = Mage::getModel('mageplace_hideprice/hideprice')->getCategoriesPriorities(array(0, self::$storeId));
        }

        return self::$higherPriorityCategories;
    }

    public static function isNeedToBeHidden()
    {
        return 1 != self::$_isCustomerGroup || !self::$_flagGroup;
    }
}
