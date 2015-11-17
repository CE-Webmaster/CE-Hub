<?php
class Addedbytes_Massredirect_Adminhtml_Model_System_Config_Backend_Massredirect extends Mage_Core_Model_Config_Data
{
    /**
     * Pass file to import handler function.
     */
    public function _afterSave()
    {
        Mage::getResourceModel('massredirect_import/import')->uploadAndImport($this);
    }
}
