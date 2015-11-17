<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

/**
 * Class Mageplace_Backup_Block_Adminhtml_Backup_Js_Item
 */
class Mageplace_Backup_Block_Adminhtml_Backup_Js_Item extends Mageplace_Backup_Block_Adminhtml_Backup_Js
{
    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('mpbackup/backup/js/item.phtml');
    }

    public function getBackupItem()
    {
        return $this->getBackupItemObject()->getStaticData();
    }

    public function getBackupItemObject()
    {
        return Mage::getSingleton('mpbackup/backup_item');
    }
}