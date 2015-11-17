<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

/**
 * Class Mageplace_Backup_Model_Source_Abstract
 */
abstract class Mageplace_Backup_Model_Source_Abstract
{
    abstract public function toOptionArray();

    public function toOptionHash()
    {
        $hash = array();
        foreach ($this->toOptionArray() as $item) {
            $hash[$item['value']] = $item['label'];
        }

        return $hash;
    }

    /**
     * @return Mageplace_Backup_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('mpbackup');
    }
}
