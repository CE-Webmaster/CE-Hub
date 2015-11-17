<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


class Mageplace_Hideprice_Block_Adminhtml_Catalog_Product_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'hp_id';
        $this->_blockGroup = 'mageplace_hideprice';
        $this->_controller = 'adminhtml_catalog_product';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Settings'));
    }

    /**
     * Add child HTML to layout
     *
     * @return Mage_Adminhtml_Block_Tag_Edit
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->setChild('store_switcher', $this->getLayout()->createBlock('mageplace_hideprice/adminhtml_store_switcher'))
            ->setChild('product_grid', $this->getLayout()->createBlock('mageplace_hideprice/adminhtml_catalog_product_grid'));

        return $this;
    }

    /**
     * Retrieve Header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        return $this->__('Products Settings');
    }

    /**
     * Retrieve Assigned Tags Accordion HTML
     *
     * @return string
     */
    public function getProductGridHtml()
    {
        return $this->getChildHtml('product_grid');
    }

    /**
     * Retrieve Store Switcher HTML
     *
     * @return string
     */
    public function getStoreSwitcherHtml()
    {
        return $this->getChildHtml('store_switcher');
    }

    /**
     * Check whether it is single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        return Mage::app()->isSingleStoreMode();
    }

    /**
     * Retrieve Tag Save URL
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/saveProduct', array('_current' => true));
    }
}
