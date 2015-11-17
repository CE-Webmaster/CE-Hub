<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Block_Adminhtml_Profile_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	/**
	 * Preperation of current form
	 *
	 * @return Mageplace_Backup_Block_Adminhtml_Profile_Edit_Form
	 */
	protected function _prepareForm()
	{
		$model = Mage::registry('mpbackup_profile');

		$form = new Varien_Data_Form();
		$form->setId('edit_form');
		$form->setAction($this->getSaveUrl());
		$form->setMethod('post');
		$form->setUseContainer(true);
		
		$form->addField('session_id',
			'hidden',
			array(
				'name' => 'session_id',
			)
		);
		
		$form->setValues($model->getData());
		
		$this->setForm($form);

		return parent::_prepareForm();
	}

	public function getSaveUrl()
	{
		return $this->getUrl('*/*/save');
	}
}