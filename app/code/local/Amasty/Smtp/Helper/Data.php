<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Smtp
 */
class Amasty_Smtp_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function debug($str)
    {
        if (!Mage::getStoreConfig('amsmtp/general/debug'))
        {
            return;
        }
        $debug = Mage::getModel('amsmtp/debug');
        $debug->setData(array(
            'cdate' => now(),
            'message' => $str,
        ))->save();
    }

    public function log($data)
    {
        if (!Mage::getStoreConfig('amsmtp/general/log'))
        {
            return;
        }
        $log = Mage::getModel('amsmtp/log');
        $data['cdate'] = now();
        $log->setData($data)->save();
        return $log->getId();
    }

    public function logStatusUpdate($logId, $status)
    {
        if (!Mage::getStoreConfig('amsmtp/general/log'))
        {
            return;
        }
        $log = Mage::getModel('amsmtp/log')->load($logId);
        if ($log->getId())
        {
            $log->setStatus($status)->save();
        }
        return true;
    }
}