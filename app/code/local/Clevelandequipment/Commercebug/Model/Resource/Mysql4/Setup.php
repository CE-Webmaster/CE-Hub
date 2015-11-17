<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/

class Clevelandequipment_Commercebug_Model_Resource_Mysql4_Setup extends Mage_Core_Model_Resource_Setup
{
    public function getShim()
    {
        $shim = Clevelandequipment_Commercebug_Model_Shim::getInstance();
        return $shim;
    }
}