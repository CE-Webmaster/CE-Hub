<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


class Mageplace_Hideprice_Block_Adminhtml_Catalog_Product_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('hideprice_product_form');
        $this->setTitle($this->__('Hideprice Products Settings'));
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
        );

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => $this->__('Hideprice Settings')));

        $fieldset->addField('form_key', 'hidden', array(
            'name'  => 'form_key',
            'value' => Mage::getSingleton('core/session')->getFormKey(),
        ));

        $fieldset->addField('HP_mode', 'select', array(
            'name'   => 'HP_mode',
            'label'  => $this->__('Mode'),
            'title'  => $this->__('Mode'),
            'values' => Mage::getSingleton('mageplace_hideprice/option_mode')->toOptionArray(),
        ));

        $fieldset->addField('HP_group', 'multiselect', array(
            'name'   => 'HP_group',
            'label'  => $this->__('By Group'),
            'title'  => $this->__('By Group'),
            'values' => Mage::getSingleton('mageplace_hideprice/option_choose')->toOptionArray(),
        ));

        $this->setForm($form);
        return parent::_prepareForm();
    }
}
