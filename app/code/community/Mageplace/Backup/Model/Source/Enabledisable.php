<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Model_Source_Enabledisable extends Mageplace_Backup_Model_Source_Abstract
{
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => $this->_getHelper()->__('Enable')),
            array('value' => 0, 'label' => $this->_getHelper()->__('Disable')),
        );
    }
}
