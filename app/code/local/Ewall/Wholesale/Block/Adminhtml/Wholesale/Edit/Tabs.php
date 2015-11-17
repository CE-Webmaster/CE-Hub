<?php

class Ewall_Wholesale_Block_Adminhtml_Wholesale_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('wholesale_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('wholesale')->__('Wholesale Price Rule'));
  }
	//preparing left side tabs
  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('wholesale')->__('Rule Information'),
          'title'     => Mage::helper('wholesale')->__('General Information'),
          'content'   => $this->getLayout()->createBlock('wholesale/adminhtml_wholesale_edit_tab_form')->toHtml(),
      ));
	  
	  $this->addTab('category_section',array(
			'label'		=> Mage::helper('wholesale')->__('Categories'),
			'title'     => Mage::helper('wholesale')->__('Categories'),
			'content'   => $this->getLayout()->createBlock('wholesale/adminhtml_wholesale_edit_tab_categories')->toHtml(),
	  ));
	  
	  $this->addTab('product_section',array(
			'label'		=> Mage::helper('wholesale')->__('Products'),
			'title'     => Mage::helper('wholesale')->__('Products'),
			'url'       => $this->getUrl('*/*/product', array('_current' => true)),
            'class'     => 'ajax',
	  ));
     
      return parent::_beforeToHtml();
  }
}
