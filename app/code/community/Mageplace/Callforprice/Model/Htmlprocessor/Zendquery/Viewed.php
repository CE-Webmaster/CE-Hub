<?php
/**
 * Mageplace Callforprice
 *
 * @category      Mageplace
 * @package       Mageplace_Callforprice
 * @copyright     Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license       http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Callforprice_Model_Htmlprocessor_Zendquery_Viewed
    extends Mageplace_Callforprice_Model_Htmlprocessor_Zendquery_Productlist
    implements Mageplace_Callforprice_Model_Processor_Interface
{

    /**
     * @param Mage_Catalog_Model_Resource_Product_Collection $products
     * @return bool
     */
    protected function _process($products)
    {
        $processor = $this->_htmlProcessor;
        $helper = $this->_getHelper();

        foreach($products as $product){
            $product->setData('url', $product->getProductUrl());
        }

        $items = $processor->query($helper->getCssSelector('viewed_product_link'));

        if(count($items) === 0){
            return false;
        }

        $positions = array();
        foreach($items as $i => $item){
            /** @var $item DOMElement */
            $url = $item->getAttribute('href');
            $product = $products->getItemByColumnValue('url', $url);
            if(!$product){
                continue;
            }
            if($helper->isEnabledForProduct($product)){
                $positions[] = $i;
            }
        }

        $processor->replace(
            $helper->getCssSelector('viewed_product_price'), $this->_getHelper()->prepareReplacement(), $positions);

    }

}