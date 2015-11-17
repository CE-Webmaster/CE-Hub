<?php
class Ewall_Wholesale_Block_Adminhtml_Wholesale extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	//Grid view for wholesale rules
  public function __construct()
  {
    $this->_controller = 'adminhtml_wholesale';
    $this->_blockGroup = 'wholesale';
    $this->_headerText = Mage::helper('wholesale')->__('Wholesale Price Rules');
    $this->_addButtonLabel = Mage::helper('wholesale')->__('Add Rule');
    parent::__construct();
  }
}
