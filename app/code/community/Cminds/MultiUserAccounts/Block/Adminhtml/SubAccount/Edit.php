<?php

class Cminds_MultiUserAccounts_Block_Adminhtml_SubAccount_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'cminds_multiuseraccounts';
        $this->_controller = 'adminhtml_subAccount';
        $this->_objectId = 'id';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('adminhtml')->__('Save Sub Account'));
        $this->_removeButton('reset');
//        $this->_updateButton('delete', 'label', Mage::helper('adminhtml')->__('Delete User'));
    }

    public function getHeaderText()
    {
        $subAccount = Mage::registry('sub_account');
        if ($subAccount && $subAccount->getId()) {
            return Mage::helper('cminds_multiuseraccounts')->__("Edit Sub Account '%s'", $this->escapeHtml($subAccount->getName()));
        } else {
            return Mage::helper('cminds_multiuseraccounts')->__('New Sub Account');
        }
    }

    public function getBackUrl()
    {
        $subAccount = Mage::registry('sub_account');
        return $this->getUrl('*/customer/edit/tab/customer_info_tabs_customer_edit_tab_subaccount', array('id' => $subAccount->getParentCustomerId()));
    }


    /**
     * Get validation url
     *
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }

}