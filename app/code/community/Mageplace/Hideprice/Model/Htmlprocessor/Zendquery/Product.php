<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


class Mageplace_Hideprice_Model_Htmlprocessor_Zendquery_Product
    implements Mageplace_Hideprice_Model_Processor_Interface
{
    /** @var  $_htmlProcessor Mageplace_Hideprice_Model_Htmlprocessor_Interface */
    private $_htmlProcessor;

    public function setHtmlProcessor($processor)
    {
        $this->_htmlProcessor = $processor;
    }

    public function process($params)
    {
        /** @var $productsCollection Mage_Catalog_Model_Resource_Product_Collection */
        $productsCollection = $params['products'];
        $message            = $params['message'];
        $blockType          = $params['block_type'];
        $this->_processProducts($productsCollection, $message, $blockType);

        return true;
    }

    /**
     * @param $productsCollection Mage_Catalog_Model_Resource_Product_Collection
     * @param $message            string
     * @param $blockType          string
     *
     * @return bool
     */
    private function _processProducts($productsCollection, $message, $blockType)
    {
        $processor = $this->_htmlProcessor;
        $helper    = $this->_getHelper();

        $products = array();
        foreach ($productsCollection as $product) {
            if ($product instanceof Mage_Wishlist_Model_Item) {
                $wishlistProduct                             = $product->getProduct();
                $products[$wishlistProduct->getProductUrl()] = $wishlistProduct;
            } elseif (is_array($productsCollection)) {
                $products[$product->getProductUrl()] = $product;
            } else {
                $product->setData('url', $product->getProductUrl());
            }
        }

        $linkSelectors = array_map('trim', explode(Mageplace_Hideprice_Model_Observer::DELIMITER, $helper->getCssSelector('product_link')));
        foreach ($linkSelectors as $linkSelector) {
            $items = $processor->query($linkSelector);
            if (count($items) === 0) {
                continue;
            }

            $positions = array();
            foreach ($items as $i => $item) {
                /** @var $item DOMElement */
                $url = $item->getAttribute('href');
                if ($blockType == 'wishlist/customer_sidebar'
                    || $blockType == 'wishlist/customer_wishlist_items'
                    || $blockType == 'tag/customer_view'
                ) {
                    $product = empty($products[$url]) ? false : $products[$url];
                } else {
                    $product = $productsCollection->getItemByColumnValue('url', $url);
                }

                /** @var $product Mage_Catalog_Model_Product */
                if (!$product) {
                    continue;
                }

                if (Mageplace_Hideprice_Model_Observer::$productEnable) {
                    /** @var $hideModel Mageplace_Hideprice_Model_Hideprice */
                    $hideModel = Mage::getModel('mageplace_hideprice/hideprice');
                    $hideModel->loadByProductId($product->getId(), array(0, Mageplace_Hideprice_Model_Observer::$storeId));
                }

                $skip = false;
                if (Mageplace_Hideprice_Model_Observer::$productEnable && $hideModel->getId() && $hideModel->isShowprice(Mageplace_Hideprice_Model_Observer::$groupId)) {
                    $skip = true;
                } elseif (Mageplace_Hideprice_Model_Observer::$productEnable && $hideModel->getId() && $hideModel->isHideprice(Mageplace_Hideprice_Model_Observer::$groupId)) {
                    $positions[] = $i;
                    $skip        = true;
                } elseif (Mageplace_Hideprice_Model_Observer::$categoryEnable) {
                    if (($categoriesIds = $product->getCategoryIds()) && is_array($categoriesIds)) {
                        $categories             = Mageplace_Hideprice_Model_Observer::categoriesPriorities();
                        $intersectCategoriesIds = array_intersect($categories, $categoriesIds);
                        if (!empty($intersectCategoriesIds)) {
                            foreach ($intersectCategoriesIds as $categoryId) {
                                $category = Mage::getModel('catalog/category')->load($categoryId);
                                $check    = $helper->checkCategory($category);
                                $skip     = $check->getSkip();
                                if ($check->getHide()) {
                                    $positions[] = $i;
                                }
                            }
                        } else {
                            if (($category = $product->getCategory())
                                && is_object($category)
                                && ($categoryId = $category->getId())
                                && in_array($categoryId, $categoriesIds)
                            ) {
                                $check = $helper->checkCategory($category);
                                $skip  = $check->getSkip();
                                if ($check->getHide()) {
                                    $positions[] = $i;
                                }
                            } else {
                                foreach ($categoriesIds as $categoryId) {
                                    $category = Mage::getModel('catalog/category')->load($categoryId);
                                    $check    = $helper->checkCategory($category);
                                    $skip     = $check->getSkip();
                                    if ($check->getHide()) {
                                        $positions[] = $i;
                                    }
                                }
                            }
                        }
                    }
                }

                if (!$skip && Mageplace_Hideprice_Model_Observer::isNeedToBeHidden()) {
                    $positions[] = $i;
                }
            }

            $positions = array_unique($positions);
            if (count($positions) > 0) {
                $messageReplaced = false;

                if ($helper->isFilterEnable('price')) {
                    if ($selector = $helper->getCssSelector('price')) {
                        $this->_replaceSelector($processor, $blockType, $selector, $message, $positions, $messageReplaced);
                    }
                }

                // add to cart button
                if ($helper->isFilterEnable('add_to_cart')) {
                    if ($selector = $helper->getCssSelector('add_to_cart')) {
                        $this->_replaceSelector($processor, $blockType, $selector, $message, $positions, $messageReplaced);
                    }

                    if ($selector = $helper->getCssSelector('add_to_cart_link')) {
                        $this->_replaceSelector($processor, $blockType, $selector, $message, $positions, $messageReplaced);
                    }
                }

                // product qty
                if ($helper->isFilterEnable('qty')) {
                    if ($selector = $helper->getCssSelector('qty_label')) {
                        $this->_replaceSelector($processor, $blockType, $selector, $message, $positions, $messageReplaced);
                    }

                    if ($selector = $helper->getCssSelector('qty')) {
                        $this->_replaceSelector($processor, $blockType, $selector, $message, $positions, $messageReplaced);
                    }

                    if ($selector = $helper->getCssSelector('qty_bundle')) {
                        $this->_replaceSelector($processor, $blockType, $selector, $message, $positions, $messageReplaced);
                    }
                }

                // add to compare link
                if ($helper->isFilterEnable('add_to_compare')) {
                    if ($selector = $helper->getCssSelector('add_to_compare')) {
                        $this->_replaceSelector($processor, $blockType, $selector, $message, $positions, $messageReplaced);
                    }
                }

                // add to wishlist link
                if ($helper->isFilterEnable('add_to_wishlist')) {
                    if ($selector = $helper->getCssSelector('add_to_wishlist')) {
                        $this->_replaceSelector($processor, $blockType, $selector, $message, $positions, $messageReplaced);
                    }
                }
            }
        }
    }

    /**
     * Replace by selector
     *
     * @param Mageplace_Hideprice_Model_Htmlprocessor_Zendquery $processor
     * @param string $blockType
     * @param string $selectors
     * @param string $replacement
     * @param array $positions
     * @param bool $messageReplaced
     */
    protected function _replaceSelector(&$processor, $blockType, $selectors, $replacement, $positions, &$messageReplaced)
    {
        $parentSelectors = null;
        switch ($blockType) {
            case 'catalog/product_list':
                /** .products-grid .item */
                $parentSelectors = $this->_getHelper()->getCssSelector('product_list_cell');
                break;

            case 'catalog/product_new':
                /** .products-grid .item */
                $parentSelectors = $this->_getHelper()->getCssSelector('new_product_list_cell');
                break;

            case 'catalog/product_list_related':
                /** .mini-products-list .item */
                $parentSelectors = $this->_getHelper()->getCssSelector('related_product_cell');
                break;

            case 'catalog/product_list_upsell':
                /** table.products-grid td */
                $parentSelectors = $this->_getHelper()->getCssSelector('upsell_product_cell');
                break;

            case 'wishlist/customer_wishlist_items':
                /** table.compare-table tr.product-shop-row td;table.compare-table tr.add-to-row td */
                $parentSelectors = $this->_getHelper()->getCssSelector('wishlist_product_cell');
                break;

            case 'wishlist/customer_sidebar':
                /** .mini-products-list  .item */
                $parentSelectors = $this->_getHelper()->getCssSelector('my_wishlist_product_cell');
                break;

            case 'tag/customer_view':
                /** #my-tags-table tr */
                break;

            case 'reports/product_viewed':
                /** .products-grid .item */
                $parentSelectors = $this->_getHelper()->getCssSelector('reports_product_viewed');
                break;

            case 'reports/product_compared':
                /** .products-grid .item */
                $parentSelectors = $this->_getHelper()->getCssSelector('reports_product_compared');
                break;
        }


        foreach (explode(Mageplace_Hideprice_Model_Observer::DELIMITER, $selectors) as $selector) {
            if (null === $parentSelectors) {
                if ($messageReplaced) {
                    $processor->remove($selector, $positions);
                } else {
                    $processor->replace($selector, $replacement, $positions);
                    $messageReplaced = true;
                }
            } else {
                foreach (explode(Mageplace_Hideprice_Model_Observer::DELIMITER, $parentSelectors) as $parentSelector) {
                    if ($messageReplaced) {
                        $processor->remove($selector, $positions, $parentSelector);
                    } else {
                        $processor->replace($selector, $replacement, $positions, $parentSelector);
                        $messageReplaced = true;
                    }
                }
            }
        }
    }

    /**
     * @return Mageplace_Hideprice_Helper_Data
     */
    private function _getHelper()
    {
        return Mage::helper('hideprice');
    }
}