<?php
class Clevelandequipment_CommerceBugProduction_Model_Ison extends Clevelandequipment_Commercebug_Model_Ison
{
    public function isOn()
    {
        return Mage::helper('core')->isDevAllowed();
        //return $_SERVER['REMOTE_ADDR'] == '127.0.0.1';
    }
}