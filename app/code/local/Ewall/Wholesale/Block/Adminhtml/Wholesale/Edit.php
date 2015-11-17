<?php

class Ewall_Wholesale_Block_Adminhtml_Wholesale_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
	//initializing contruct
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'wholesale';
        $this->_controller = 'adminhtml_wholesale';
        
        $this->_updateButton('save', 'label', Mage::helper('wholesale')->__('Save Rule'));
        $this->_updateButton('delete', 'label', Mage::helper('wholesale')->__('Delete Rule'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('wholesale_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'wholesale_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'wholesale_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

	//getting header text for adding rule page
    public function getHeaderText()
    {
        if( Mage::registry('wholesale_data') && Mage::registry('wholesale_data')->getId() ) {
            return Mage::helper('wholesale')->__("Edit Rule '%s'", $this->htmlEscape(Mage::registry('wholesale_data')->getRulename()));
        } else {
            return Mage::helper('wholesale')->__('Add Rule');
        }
    }
    
}
