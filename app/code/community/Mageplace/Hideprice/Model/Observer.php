<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */
class Mageplace_Hideprice_Model_Observer
{
    const DELIMITER = ';';

    public static $storeId;
    public static $categoryEnable;
    public static $productEnable;
    public static $groupId;
    public static $higherPriorityCategories;

    /**
     * @var Mageplace_Hideprice_Helper_Community
     */
    protected static $_helper;
    protected static $_isEnabled;
    protected static $_blocks;
    protected static $_isReverse;
    protected static $_isCustomerGroup;
    protected static $_flagGroup;

    protected $_replacedJs = array();

    /**
     * Types of blocks, enabled for processing
     * @var array
     */
    static $_enableBlockType = array(
        'catalog/product_list',
        'catalog/product_view',
        'catalog/product_popular',
        'catalog/product_new',
        'catalog/product_list_related',
        'catalog/product_list_upsell',
        'catalog/product_list_random',
        'catalog/product_list_promotion',
        'catalog/product_list_crosssell',
        'wishlist/customer_sidebar',
        'wishlist/customer_wishlist_items',
        'tag/customer_view',
        'reports/product_viewed',
        'reports/product_compared',
    );

    public function __construct()
    {
        if (null === self::$_helper) {
            self::$_helper = Mage::helper('hideprice');
            if (!Mage::app()->isSingleStoreMode()) {
                self::$storeId = Mage::app()->getStore()->getStoreId();
            } else {
                self::$storeId = null;
            }
            self::$groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
        }
    }

    public static function isNeedToBeHidden()
    {
        return 1 != self::$_isCustomerGroup || !self::$_flagGroup;
    }

    public static function categoriesPriorities()
    {
        if (null === self::$higherPriorityCategories) {
            self::$higherPriorityCategories = Mage::getModel('mageplace_hideprice/hideprice')->getCategoriesPriorities(array(0, self::$storeId));
        }

        return self::$higherPriorityCategories;
    }

    /**
     * Processing block before output
     *
     * @param $observer
     */
    public function processCoreBlockAbstractToHtmlAfter($observer)
    {
#		$start = microtime(true);
        if (null === self::$_isEnabled) {
            if (Mage::app()->getStore()->isAdmin()) {
                self::$_isEnabled = false;
            } else {
                $storeId = Mage::app()->getStore()->getGroup()->getId();
                $stores  = self::$_helper->getStoreConfig('options/choose_stores');
                $stores  = explode(',', $stores);
                if (in_array($storeId, $stores)) {
                    self::$_isEnabled = false;
                } else {
                    self::$_isEnabled = true;
                }
            }
        }

        if (false === self::$_isEnabled) {
            return;
        }

        if (null === self::$_blocks) {
            $configBlocks = self::$_helper->getProcessingBlocks();

            $resultBlocks = array();
            foreach (self::$_enableBlockType as $blockName) {
                $blockName = trim($blockName);
                if (!empty($blockName)) {
                    $resultBlocks[] = $blockName;
                }
            }

            self::$_blocks = array_unique(array_merge($resultBlocks, $configBlocks));
        }

        $blockType = $observer->getBlock()->getType();
#		echo '<!-- xxxx ' . $blockType . ' - ' . get_class($observer->getBlock()) . ' - ' . $observer->getBlock()->getTemplateFile() . ' xxxx -->';
        if (in_array($blockType, self::$_blocks)) {
#			Mage::log($blockType);
#			$start2 = microtime(true);
            if (null === self::$_isReverse) {
                self::$_isReverse = self::$_helper->getStoreConfig('options/reverse');
            }

            $html = $observer->getTransport()->getHtml();
            if ('' !== $html && trim($html)) {
                $this->_replacedJs = array();

                if (strpos($html, '<script') !== false) {
                    $html = preg_replace_callback('#(\<script[^\>]*\>)(.*?)(\<\/script\>)#ims', array($this, '_replaceJS'), $html);
                }

#				$start3 = microtime(true);
                $this->_hidePriceLogic($html, $observer, $blockType);
#				Mage::log((microtime(true) - $start3) * 1000000);

                foreach ($this->_replacedJs as $key => $js) {
                    $html = str_replace('{{HIDEPRICE_' . $key . '}}', $js, $html);
                }

                $observer->getTransport()->setHtml($html);

                $this->_replacedJs = array();
            }

#			Mage::log((microtime(true) - $start2) * 1000000);
#			Mage::log('---');
        }
#		Mage::log((microtime(true) - $start) * 1000000);
#			Mage::log('-----');
    }

    /**
     * Hide price in block
     *
     * @param $html
     * @param $observer
     * @param $blockType
     */
    protected function _hidePriceLogic(&$html, $observer, $blockType)
    {
        if (null === self::$_isCustomerGroup) {
            self::$_isCustomerGroup = (int)self::$_helper->getStoreConfig('options/customer_group_enable');
            self::$categoryEnable   = (bool)self::$_helper->getStoreConfig('options/category_enable');
            self::$productEnable    = (bool)self::$_helper->getStoreConfig('options/product_enable');
        }

        if (1 == self::$_isCustomerGroup || self::$categoryEnable || self::$productEnable) {
            if (null === self::$_flagGroup) {
                $groupsString = self::$_helper->getStoreConfig('options/choose_customer_groups');

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
                $this->checkProducts($html, $observer, $blockType);
            } else {
                $this->deleteButtonsAndPrice($html, $observer, self::$_helper->getStoreConfig('options/text_message_group'));
            }

        } else {
            $this->deleteButtonsAndPrice($html, $observer, self::$_helper->getStoreConfig('options/text_message'));
        }
    }


    public function checkProducts(&$html, $observer, $blockType)
    {
        $message = self::$_helper->getStoreConfig('options/text_message_product');

        /** @var $productCollection Mage_Catalog_Model_Resource_Product_Collection */
        $productCollection = $observer->getBlock()->getLoadedProductCollection();
        if (!$productCollection instanceof Varien_Data_Collection) {
            $productCollection = $observer->getBlock()->getItemCollection();
        }
        if (!$productCollection instanceof Varien_Data_Collection) {
            $productCollection = $observer->getBlock()->getItemsCollection();
        }
        if (!$productCollection instanceof Varien_Data_Collection) {
            $productCollection = $observer->getBlock()->getProductCollection();
        }
        if (!$productCollection instanceof Varien_Data_Collection) {
            $productCollection = $observer->getBlock()->getItems();
        }
        if (!$productCollection instanceof Varien_Data_Collection && $blockType == 'wishlist/customer_sidebar') {
            $productCollection = $observer->getBlock()->getWishlistItems();
        }
        if (!$productCollection instanceof Varien_Data_Collection && $blockType == 'tag/customer_view') {
            $productCollection = $observer->getBlock()->getMyProducts();
        }

        if (!is_null($productCollection)) {
            /** @var $processor Mageplace_Hideprice_Model_Htmlprocessor_Interface|Mageplace_Hideprice_Model_Htmlprocessor_Zendquery_Product */
            $processor = $processor = $this->_getProcessor($html);

            $processor->process('product', array(
                'products'   => $productCollection,
                'block_type' => $blockType,
                'message'    => self::$_helper->prepareReplacement($message)
            ));

            $html = $processor->getHtml();
        }

        if ($currentProduct = Mage::registry('current_product')) {
            if (self::$productEnable) {
                /** @var $hideModel Mageplace_Hideprice_Model_Hideprice */
                $hideModel = Mage::getModel('mageplace_hideprice/hideprice')
                    ->loadByProductId($currentProduct->getId(), array(0, self::$storeId));
            }

            if (self::$productEnable && $hideModel->getId() && $hideModel->isShowprice(self::$groupId)) {
                $skip = true;
            } elseif (self::$productEnable && $hideModel->getId() && $hideModel->isHideprice(self::$groupId)) {
                $this->deleteButtonsAndPrice($html, $observer, $message, array('price' => self::$_helper->getCssSelector('product_page_price')));
                $skip = true;
            } elseif (self::$categoryEnable) {
                $skipCheckCategory = false;
                $categories        = self::categoriesPriorities();
                $categoriesIds     = $currentProduct->getCategoryIds();
                if (is_array($categoriesIds)) {
                    $intersectCategoriesIds = array_intersect($categories, $categoriesIds);
                    if (!empty($intersectCategoriesIds)) {
                        $skipCheckCategory = true;
                        foreach ($intersectCategoriesIds as $categoryId) {
                            $skip = $this->checkCategory($html, $observer, $categoryId);
                        }
                    }
                }

                if (!$skipCheckCategory) {
                    if (is_object($currentProduct->getCategory()) && ($categoryId = $currentProduct->getCategory()->getId())) {
                        $skip = $this->checkCategory($html, $observer, $categoryId);
                    } elseif (is_array($categoriesIds)) {
                        foreach ($categoriesIds as $categoryId) {
                            $skip = $this->checkCategory($html, $observer, $categoryId);
                        }
                    }
                }
            }

            if (empty($skip) && self::isNeedToBeHidden()) {
                $this->deleteButtonsAndPrice($html, $observer, $message, array('price' => self::$_helper->getCssSelector('product_page_price')));
            }
        }
    }

    /**
     * Check is category hideprice enabled
     *
     * @param      $html
     * @param      $observer
     * @param null $category
     * @return bool
     */
    public function checkCategory(&$html, $observer, $category = null)
    {
        if (null !== $category) {
            $category = Mage::getModel('catalog/category')->load($category);
            $path     = explode('/', $category->getPath());
            $selector = array('price' => self::$_helper->getCssSelector('product_page_price'));
        } else {
            if (Mage::registry('current_category')) {
                $path = explode('/', Mage::registry('current_category')->getPath());
            } else {
                $path = array();
            }
            $selector = array();
        }

        $curCategory = array_pop($path);

        /** @var $hideModel Mageplace_Hideprice_Model_Hideprice */
        $hideModel = Mage::getModel('mageplace_hideprice/hideprice')
            ->loadByCategoryId($curCategory, array(0, self::$storeId));

        $hide = false;
        $skip = false;
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

        if ($hide) {
            $this->deleteButtonsAndPrice($html, $observer, self::$_helper->getStoreConfig('options/text_message_category'), $selector);
        }

        return $skip;
    }


    public function deleteButtonsAndPrice(&$html, $observer, $message, $selectors = array())
    {
        if (empty($html)) {
            return;
        }

        $processor = $this->_getProcessor($html);

        $messageReplaced = false;

        $replacement = self::$_helper->prepareReplacement($message);

        // replace price with info block
        if (self::$_helper->isFilterEnable('price')) {
            if ($selector = array_key_exists('price', $selectors) ? $selectors['price'] : self::$_helper->getCssSelector('price')) {
                $this->_replaceSelector($processor, $selector, $replacement, $messageReplaced);
            }

            if ($selector = array_key_exists('bundle_price', $selectors) ? $selectors['bundle_price'] : self::$_helper->getCssSelector('bundle_price')) {
                $this->_replaceSelector($processor, $selector, $replacement, $messageReplaced);
            }
        }

        // add to cart button
        if (self::$_helper->isFilterEnable('add_to_cart')) {
            if ($selector = array_key_exists('add_to_cart', $selectors) ? $selectors['add_to_cart'] : self::$_helper->getCssSelector('add_to_cart')) {
                $this->_replaceSelector($processor, $selector, $replacement, $messageReplaced);
            }
            if ($selector = array_key_exists('add_to_cart_link', $selectors) ? $selectors['add_to_cart_link'] : self::$_helper->getCssSelector('add_to_cart_link')) {
                $this->_replaceSelector($processor, $selector, $replacement, $messageReplaced);
            }
        }

        // product qty
        if (self::$_helper->isFilterEnable('qty')) {
            if ($selector = array_key_exists('qty_label', $selectors) ? $selectors['qty_label'] : self::$_helper->getCssSelector('qty_label')) {
                $this->_replaceSelector($processor, $selector, $replacement, $messageReplaced);
            }
            if ($selector = array_key_exists('qty', $selectors) ? $selectors['qty'] : self::$_helper->getCssSelector('qty')) {
                $this->_replaceSelector($processor, $selector, $replacement, $messageReplaced);
            }
            if ($selector = array_key_exists('qty_bundle', $selectors) ? $selectors['qty_bundle'] : self::$_helper->getCssSelector('qty_bundle')) {
                $this->_replaceSelector($processor, $selector, $replacement, $messageReplaced);
            }
        }

        // add to compare link
        if (self::$_helper->isFilterEnable('add_to_compare')) {
            if ($selector = array_key_exists('add_to_compare', $selectors) ? $selectors['add_to_compare'] : self::$_helper->getCssSelector('add_to_compare')) {
                $this->_replaceSelector($processor, $selector, $replacement, $messageReplaced);
            }
        }

        // add to wishlist link
        if (self::$_helper->isFilterEnable('add_to_wishlist')) {
            if ($selector = array_key_exists('add_to_wishlist', $selectors) ? $selectors['add_to_wishlist'] : self::$_helper->getCssSelector('add_to_wishlist')) {
                $this->_replaceSelector($processor, $selector, $replacement, $messageReplaced);
            }
        }

        $html = $processor->getHtml();
    }

    protected function _replaceJS($matches)
    {
        $this->_replacedJs[] = $matches[2];

        return $matches[1] . '{{HIDEPRICE_' . (count($this->_replacedJs) - 1) . '}}' . $matches[3];
    }

    protected function _getProcessor($html)
    {
        /** @var $processor Mageplace_Hideprice_Model_Htmlprocessor_Interface */
        $processor = Mage::getModel('mageplace_hideprice/htmlprocessor_factory')->createProcessor();
        // loading html in processor
        $processor->load($html);

        return $processor;
    }

    protected function _replaceSelector(&$processor, $selectors, $replacement, &$messageReplaced)
    {
        foreach (explode(self::DELIMITER, $selectors) as $selector) {
            if ($messageReplaced) {
                $processor->remove($selector);
            } else {
                $processor->replace($selector, $replacement);
                $messageReplaced = true;
            }
        }
    }
}