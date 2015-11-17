<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */
class Mageplace_Backup_Model_Source_AmazonS3Regions extends Mageplace_Backup_Model_Source_Abstract
{
    public function toOptionArray()
    {
        $regions = array();

        foreach(Mageplace_Backup_Model_Cloud_Amazons3::$REGIONS as $regionCode => $regionName) {
            $regions[] = array('value' => $regionCode, 'label' => $this->_getHelper()->__($regionName));
        }

        return $regions;
    }
}
