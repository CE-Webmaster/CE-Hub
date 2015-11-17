<?php
/**
 * Mageplace Callforprice
 *
 * @category      Mageplace
 * @package       Mageplace_Callforprice
 * @copyright     Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license       http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Callforprice_Helper_Data extends Mage_Core_Helper_Abstract
{
    const IS_DEBUG_ENABLE = false;

    const XML_PATH_ADVANCED_PROCESSOR   = 'advanced/processor';
    const XML_PATH_GLOBAL_ENABLED = 'options/global';
    const XML_PATH_PREFIX_CSS_SELECTOR  = 'selectors/';

    const XML_PATH_HREF     = 'options/href';
    const XML_PATH_MESSAGE  = 'options/text_message';

    protected $_xmlPathModulePrefix = 'callforprice/';

    /**
     * Is global call for price enabled
     * @return mixed
     */
    public function isGlobalEnabled()
    {
        return (bool)$this->getStoreConfig(self::XML_PATH_GLOBAL_ENABLED);
    }

    /**
     * Return css selector by name in config
     * @param string $name
     * @return mixed
     */
    public function getCssSelector($name)
    {
        $xmlPath = self::XML_PATH_PREFIX_CSS_SELECTOR . $name;
        return $this->getStoreConfig($xmlPath);
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
     * Return module config
     * @param string $path without module prefix
     * @return mixed
     */
    public function getStoreConfig($path)
    {
        $xmlPath = $this->_xmlPathModulePrefix . $path;
        return Mage::getStoreConfig($xmlPath);
    }

    /**
     * Return prepared message
     * @return string
     */
    public function prepareReplacement()
    {
        $href = $this->getStoreConfig(self::XML_PATH_HREF);
        $message = $this->getStoreConfig(self::XML_PATH_MESSAGE);

        if(strlen($href)){
            $href = "href='" . $href . "'";
        }

        $replacement = "<div><a class='callforprice' " . $href
            . ">" . $message
            . "</a></div>";

        return $replacement;
    }

    public function isEnabledForProduct($product)
    {
        /** @var $cfpModel Mageplace_Callforprice_Model_Callforprice */
        $cfpModel = Mage::getModel('mageplace_callforprice/callforprice');
        $cfpModel->loadByProductId($product->getId());
        return $this->_isEnabledForCustom($cfpModel);
    }

    public function isEnabledForCategory($category)
    {
        /** @var $cfpModel Mageplace_Callforprice_Model_Callforprice */
        $cfpModel = Mage::getModel('mageplace_callforprice/callforprice');
        $cfpModel->loadByCategoryId($category->getId());
        return $this->_isEnabledForCustom($cfpModel);
    }


    private function _isEnabledForCustom($cfpModel)
    {
        if(!$cfpModel->getId()){
            return false;
        }

        //$this->_dump($cfpModel);

        /** @var $customerSession Mage_Customer_Model_Session  */
        $customerSession = Mage::getSingleton('customer/session');
        $storeId = Mage::app()->getStore()->getId();

        $isEnabled = $cfpModel->isCallforprice()
            && !in_array($customerSession->getCustomerGroupId(), $cfpModel->getCustomerGroups())
            && ( (count($cfpModel->getCustomerIds()) === 0) ||  in_array($customerSession->getCustomerId(), $cfpModel->getCustomerIds()) )
            && !in_array($storeId, $cfpModel->getStoreIds());

        return $isEnabled;
    }


    /**
     * Dump model state
     *
     * @param $cfpModel
     * @param bool $isReturn
     * @return string
     */
    private function _dump($cfpModel, $isReturn = false)
    {
        if(!self::IS_DEBUG_ENABLE){
            return;
        }

        /** @var $customerSession Mage_Customer_Model_Session  */
        $customerSession = Mage::getSingleton('customer/session');
        $storeId = Mage::app()->getStore()->getId();

        ob_start();
        echo 'is_cfp - ';
        var_dump($cfpModel->isCallforprice());
        echo 'customer_group - ';
        var_dump(!in_array($customerSession->getCustomerGroupId(), $cfpModel->getCustomerGroups()), $cfpModel->getCustomerGroups());
        echo 'customer_ids - ';
        var_dump(( (count($cfpModel->getCustomerIds()) === 0) ||  in_array($customerSession->getCustomerId(), $cfpModel->getCustomerIds()) ), $cfpModel->getCustomerIds());
        echo 'stores_ids - ';
        var_dump(!in_array($storeId, $cfpModel->getStoreIds()), $cfpModel->getStoreIds());
        $out = ob_get_clean();

        if($isReturn){
            return $out;
        } else {
            echo $out;
        }
    }
}