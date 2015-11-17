<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Callforprice
 */
class Mageplace_Callforprice_Model_Htmlprocessor_Zendquery_Wishlist
    extends Mageplace_Callforprice_Model_Htmlprocessor_Zendquery_Productlist
    implements Mageplace_Callforprice_Model_Processor_Interface
{

    /**
     * @param Mage_Catalog_Model_Resource_Product_Collection $products
     * @return bool
     */
    protected function _process($items)
    {
        $processor = $this->_htmlProcessor;
        $helper    = $this->_getHelper();

        $positions = array();
        $i         = 0;
        foreach ($items as $item) {
			$product = Mage::getModel('catalog/product')->load($item->getProductId());
            if ($helper->isEnabledForProduct($product)) {
                $positions[] = $i;
            }
            $i++;
        }

        $processor->replace(
            $helper->getCssSelector('wishlist_product_price'),
            $this->_getHelper()->prepareReplacement(),
            $positions
        );

        $processor->replace(
            $helper->getCssSelector('wishlist_product_addtocart'),
            '',
            $positions
        );

        if ($positions) {
            $processor->remove($helper->getCssSelector('wishlist_product_all_addtocart'));
        }
    }
}