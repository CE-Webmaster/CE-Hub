<?php
/*
 * Developer: Michael Jacky
* Team site: http://cmsideas.net/
* Support: http://support.cmsideas.net/
*
*/
class Glace_Productlabels_Block_Adminhtml_Productlabels_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('glace_productlabels/form/container.phtml');
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'productlabels';
        $this->_controller = 'adminhtml_productlabels';
        
        $this->_updateButton('save', 'label', '<i class="fa fa-hand-o-down fa-2x"></i>'.Mage::helper('productlabels')->__('Save Label'));
        $this->_updateButton('delete', 'label', '<i class="fa fa-hand-o-down fa-2x"></i>'.Mage::helper('productlabels')->__('Delete Label'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => '<i class="fa fa-hand-o-down fa-2x"></i>'.Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('productlabels_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'productlabels_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'productlabels_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            function deletePicture(id)
            {
                $(id + '-pic').setStyle({'display':'none'});
                $('no' + id).value = 1;
            }
        ";
    }

    /**
     * Return save url for edit form
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('_current'=>true, 'back'=>null));
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/*/',array('store' => $this->getRequest()->getParam('store',0)));
    }

    public function getHeaderText()
    {
        if( Mage::registry('productlabels_data') && Mage::registry('productlabels_data')->getId() ) {
            return '<i class="fa fa-bolt fa-2x"></i>'.Mage::helper('productlabels')->__("Edit Label '%s'", $this->htmlEscape(Mage::registry('productlabels_data')->getName()));
        } else {
            return '<i class="fa fa-bolt fa-2x"></i>'.Mage::helper('productlabels')->__('Add Label');
        }
    }
	
	
}