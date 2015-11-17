<?php

class Cminds_MultiUserAccounts_Block_Adminhtml_SubAccount_Edit_Renderer_Newpass
    extends Mage_Adminhtml_Block_Customer_Edit_Renderer_Newpass
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Render block
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html  = '<tr>';
        $html .= '<td class="label">' . $element->getLabelHtml() . '</td>';
        $html .= '<td class="value">' . $element->getElementHtml() . '</td>';
        $html .= '</tr>' . "\n";
//        $html .= '<tr>';
//        $html .= '<td class="label"><label>&nbsp;</label></td>';
//        $html .= '<td class="value">' . Mage::helper('customer')->__('or') . '</td>';
//        $html .= '</tr>' . "\n";
//        $html .= '<tr>';
//        $html .= '<td class="label"><label>&nbsp;</label></td>';
//        $html .= '<td class="value"><input type="checkbox" id="account-send-pass" name="'
//            . $element->getName()
//            . '" value="auto" onclick="setElementDisable(\''
//            . $element->getHtmlId()
//            . '\', this.checked)"/>&nbsp;';
//        $html .= '<label for="account-send-pass">'
//            . Mage::helper('customer')->__('Send Auto-Generated Password')
//            . '</label></td>';
//        $html .= '</tr>'."\n";

        return $html;
    }
}
