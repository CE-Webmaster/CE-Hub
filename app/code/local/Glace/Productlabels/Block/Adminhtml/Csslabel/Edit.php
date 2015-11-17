<?php
/*
 * Developer: Michael Jacky
* Team site: http://cmsideas.net/
* Support: http://support.cmsideas.net/
*
*/
class Glace_Productlabels_Block_Adminhtml_Csslabel_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('glace_productlabels/form/containercss.phtml');
        $this->_objectId = Mage::registry('productlabels_css_data')->getId();
        $this->_blockGroup = 'productlabels';
        $this->_controller = 'adminhtml_csslabel';
        $this->_removeButton('back');
        $this->_removeButton('reset');
        $this->_removeButton('delete');
        $this->_addButton('cancel', array(
            'label'     => Mage::helper('adminhtml')->__('Cancel'),
            'onclick'   => 'setLocation(\'' . $this->getCancelUrl() . '\')',
            'class'     => 'back',
        ), -1);
        $this->_updateButton('save', 'label', '<i class="fa fa-hand-o-down fa-2x"></i>'.Mage::helper('productlabels')->__('Save Css'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => '<i class="fa fa-hand-o-down fa-2x"></i>'.Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('productlabels_css_data') && Mage::registry('productlabels_css_data')->getId() ) {
            return '<i class="fa fa-bolt fa-2x"></i>'.Mage::helper('productlabels')->__("Edit Css");
        } else {
            return '<i class="fa fa-bolt fa-2x"></i>'.Mage::helper('productlabels')->__('Add Item');
        }
    }

     /**
     * Get URL for cancel button
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->getUrl('*/productlabels',array('store' => $this->getRequest()->getParam('store',0)));
    }

	public function getFormActionUrl(){
		return $this->getUrl('*/*/save', array('_current'=>true, 'back'=>null));
	}
}