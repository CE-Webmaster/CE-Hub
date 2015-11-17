<?php
/*
 * Developer: Michael Jacky
* Team site: http://cmsideas.net/
* Support: http://support.cmsideas.net/
*
*/
class Glace_Productlabels_Block_Adminhtml_Element_Fieldsetcss extends Mage_Adminhtml_Block_Catalog_Form_Renderer_Fieldset_Element
{
   
    public function getAttributeCode()
    {
        return $this->getAttribute()->getNameAttribute();
    }

    public function usedDefault()
    {
        $store = $this->getRequest()->getParam('store');
        if(!$store)
            return false;
        
        return Mage::registry('productlabels_css_data')->getStore() == 0;
    }

    


}

?>
