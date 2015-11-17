<?php

class Ewall_Wholesale_Block_Adminhtml_Wholesale_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	//preparing forms
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                      'method' => 'post',
        							  'enctype' => 'multipart/form-data'
                                   )
      );

      $form->setUseContainer(true);
      $this->setForm($form);

		$form->addField('in_featured_products', 'hidden', array(
			  'name' => 'in_featured_products',
		));
      return parent::_prepareForm();
  }

}
