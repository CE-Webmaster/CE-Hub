<?php
/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Edit
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Edit
 */
class Me_Econtacts_Block_Adminhtml_Econtacts_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize edit form container
     */
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'me_econtacts';
        $this->_controller = 'adminhtml_econtacts';

        parent::__construct();

        if (Mage::helper('me_econtacts/admin')->isActionAllowed('save')) {
            $message = Mage::helper('me_econtacts')->__('Are you sure you want to send answer email to customer?');
            $this->addButton(
                'send',
                array(
                    'label' => Mage::helper('me_econtacts')->__('Send Answer'),
                    'onclick'   => "if(confirm('{$message}')) { send(); }",
                )
            );
        }

        if (Mage::helper('me_econtacts/admin')->isActionAllowed('save')) {
            $this->_updateButton('save', 'label', Mage::helper('me_econtacts')->__('Save Contact'));
            $this->_addButton(
                'saveandcontinue',
                array(
                    'label' => Mage::helper('me_econtacts')->__('Save and Continue Edit'),
                    'onclick' => 'saveAndContinueEdit()',
                    'class' => 'save',
                ),
                -100
            );
        } else {
            $this->_removeButton('save');
        }

        if (Mage::helper('me_econtacts/admin')->isActionAllowed('delete')) {
            $this->_updateButton('delete', 'label', Mage::helper('me_econtacts')->__('Delete Contact'));
        } else {
            $this->_removeButton('delete');
        }

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }

            function send(){

                    editForm.submit($('edit_form').action+'send/edit/');

            }
        ";
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        $model = Mage::registry('contact_item');
        if ($model->getId()) {
            return Mage::helper('me_econtacts')->__("Contact from '%s'", $this->escapeHtml($model->getName()));
        } else {
            return '';
        }
    }

    /**
     * Get send url
     *
     * @return string
     */
    public function getSendUrl()
    {
        return $this->getUrl('*/*/send');
    }
}
