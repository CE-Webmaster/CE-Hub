<?php

class MagicToolbox_MagicScroll_Block_Adminhtml_Catalog_Product_Edit_Tab_Images extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface {

    protected function _prepareForm() {
        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('group_fields_magicscroll_images', array('legend' => Mage::helper('magicscroll')->__('Images'), 'class' => 'magicscrollFieldset'));
        $fieldset->addType('magicscroll_gallery', 'MagicToolbox_MagicScroll_Block_Adminhtml_Settings_Edit_Tab_Form_Element_Gallery');
        $fieldset->addField('magicscroll_gallery', 'magicscroll_gallery', array(
            'label'     => Mage::helper('magicscroll')->__('${too.id} gallery'),
            'name'      => 'magicscroll[gallery]',
        ));
        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function getTabLabel() {
        return $this->__('Magic Scroll&#8482; Images');
    }

    public function getTabTitle() {
        return $this->__('Magic Scroll&#8482; Images');
    }

    public function canShowTab() {
        return true;
    }

    public function isHidden() {
        return false;
    }

    public function getHtmlId() {
        return $this->getId();
    }

    public function getJsObjectName() {
        return $this->getHtmlId().'JsObject';
    }

}

