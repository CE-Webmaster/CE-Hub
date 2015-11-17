<?php
class Addedbytes_Massredirect_Adminhtml_Block_System_Config_Disabled extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Disable upload input on config form (the form is still displayed, but
     * with a message telling the user they must select a website view to
     * upload a new file.
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $element->setDisabled('disabled');
        return parent::_getElementHtml($element);
    }
}
