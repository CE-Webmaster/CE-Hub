<?php

class Cminds_MultiUserAccounts_Block_Adminhtml_SubAccount_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Initialize form
     *
     * @return Mage_Adminhtml_Block_Customer_Edit_Tab_Account
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
        ));

        $form->setHtmlIdPrefix('_subaccount');
        $form->setFieldNameSuffix('subaccount');

        $subAccount = Mage::registry('sub_account');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('cminds_multiuseraccounts')->__('Account Information')
        ));

        $fieldset->addField('parent_customer_id', 'hidden',
            array(
                'name' => 'parent_customer_id',
                'required' => true,
            )
        );

        // New customer password
        $fieldset->addField('firstname', 'text',
            array(
                'label' => Mage::helper('cminds_multiuseraccounts')->__('First Name'),
                'name' => 'firstname',
                'required' => true,
            )
        );

        $fieldset->addField('lastname', 'text',
            array(
                'label' => Mage::helper('cminds_multiuseraccounts')->__('Last Name'),
                'name' => 'lastname',
                'required' => true
            )
        );

        $fieldset->addField('email', 'text',
            array(
                'label' => Mage::helper('cminds_multiuseraccounts')->__('Email'),
                'name' => 'email',
                'required' => true
            )
        );

        $fieldset->addField('permission', 'select', array(
            'label' => Mage::helper('cminds_multiuseraccounts')->__('Permission'),
            'name' => 'permission',
            'values' => Mage::getModel('cminds_multiuseraccounts/subAccount_permission')->getAllOptions(),
            'required' => true
        ));

        // Add password management fieldset
        $newFieldset = $form->addFieldset(
            'password_fieldset',
            array('legend' => Mage::helper('cminds_multiuseraccounts')->__('Password Management'))
        );
        // New customer password
        $field = $newFieldset->addField('new_password', 'text',
            array(
                'label' => Mage::helper('cminds_multiuseraccounts')->__('New Password'),
                'name' => 'new_password',
                'class' => 'validate-new-password'
            )
        );
        $field->setRenderer($this->getLayout()->createBlock('cminds_multiuseraccounts/adminhtml_subAccount_edit_renderer_newpass'));

//        // Prepare customer confirmation control (only for existing customers)
        $confirmationKey = $subAccount->getConfirmation();
        if ($confirmationKey || $subAccount->isConfirmationRequired()) {
            $fieldset->addField('confirmation', 'select', array(
                'name' => 'confirmation',
                'label' => Mage::helper('cminds_multiuseraccounts')->__('Confirmation'),
                'values' => array(
                    array(
                        'value' => 0,
                        'label' => Mage::helper('cminds_multiuseraccounts')->__('Not Confirmation')
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('cminds_multiuseraccounts')->__('Confirmation')
                    )
                ),
            ));

            // Prepare send welcome email checkbox if customer is not confirmed
            // no need to add it, if website ID is empty
//            if ($subAccount->getConfirmation() && $subAccount->getWebsiteId()) {
//                $fieldset->addField('sendemail', 'checkbox', array(
//                    'name' => 'sendemail',
//                    'label' => Mage::helper('cminds_multiuseraccounts')->__('Send Welcome Email after Confirmation')
//                ));
//                $subAccount->setData('sendemail', '1');
//            }
        }

        // Make sendemail and sendmail_store_id disabled if website_id has empty value
//        $isSingleMode = Mage::app()->isSingleStoreMode();
//        $sendEmailId = $isSingleMode ? 'sendemail' : 'sendemail_store_id';
//        $sendEmail = $form->getElement($sendEmailId);
//
//        $prefix = $form->getHtmlIdPrefix();
//        if ($sendEmail) {
//            $_disableStoreField = '';
//            if (!$isSingleMode) {
//                $_disableStoreField = "$('{$prefix}sendemail_store_id').disabled=(''==this.value || '0'==this.value);";
//            }
//            $sendEmail->setAfterElementHtml(
//                '<script type="text/javascript">'
//                . "
//                $('{$prefix}website_id').disableSendemail = function() {
//                    $('{$prefix}sendemail').disabled = ('' == this.value || '0' == this.value);" .
//                $_disableStoreField
//                . "}.bind($('{$prefix}website_id'));
//                Event.observe('{$prefix}website_id', 'change', $('{$prefix}website_id').disableSendemail);
//                $('{$prefix}website_id').disableSendemail();
//                "
//                . '</script>'
//            );
//        }

        $form->setUseContainer(true);
        $form->setValues($subAccount->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
