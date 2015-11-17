<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Block_Adminhtml_Backup_Grid_Column_Renderer_Errors
	extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/**
	 * Renders grid column
	 *
	 * @param Varien_Object $row
	 * @return string
	 */
	public function render(Varien_Object $row)
	{
		if ($data = $row->getData($this->getColumn()->getIndex())) {
			return '<strong style="color:red;">' . Mage::helper('adminhtml')->__('Yes') . '</strong>';
		}
		
		return '<strong style="color:green;">' . Mage::helper('adminhtml')->__('No') . '</strong>';
	}
}
