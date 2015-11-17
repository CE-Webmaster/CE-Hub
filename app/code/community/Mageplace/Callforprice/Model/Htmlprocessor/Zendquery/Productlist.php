<?php
/**
 * Mageplace Callforprice
 *
 * @category      Mageplace
 * @package       Mageplace_Callforprice
 * @copyright     Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license       http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Callforprice_Model_Htmlprocessor_Zendquery_Productlist
 implements Mageplace_Callforprice_Model_Processor_Interface
{

    /** @var  $_htmlProcessor Mageplace_Callforprice_Model_Htmlprocessor_Interface */
    protected $_htmlProcessor;

    public function setHtmlProcessor($processor)
    {
        $this->_htmlProcessor = $processor;
    }

    public function process($params)
    {
        /** @var $products Mage_Catalog_Model_Resource_Product_Collection */
        $products = $params['products'];
        return $this->_process($products);
    }

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

        $items = $processor->query($helper->getCssSelector('product_link'));

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
            $helper->getCssSelector('product_list_price'), $this->_getHelper()->prepareReplacement(), $positions);

        $processor->replace(
            $helper->getCssSelector('product_list_addtocart'), '', $positions
        );

    }

    /**
     * @return Mageplace_Callforprice_Helper_Abstract
     */
    protected function _getHelper()
    {
        return Mage::helper('mageplace_callforprice');
    }


}