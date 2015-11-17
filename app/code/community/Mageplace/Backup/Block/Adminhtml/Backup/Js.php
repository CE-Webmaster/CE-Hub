<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */
class Mageplace_Backup_Block_Adminhtml_Backup_Js extends Mage_Core_Block_Template
{
    /**
     * @return Mageplace_Backup_Model_Profile
     */
    public function getProfile()
    {
        return Mage::registry('mpbackup_profile');
    }

    /**
     * @return Mageplace_Backup_Helper_Js
     */
    public function getJsHelper()
    {
        return Mage::helper('mpbackup/js');
    }

    /**
     * @return Mageplace_Backup_Helper_Url
     */
    public function getUrlHelper()
    {
        return Mage::helper('mpbackup/url');
    }

    protected function _toHtml()
    {
        return preg_replace(array('#\<script[^\>]*\>#si', '#\<\/script\>#si'), '', parent::_toHtml());
    }
}
