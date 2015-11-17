<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

/**
 * Class Mageplace_Backup_Block_Backup_Item
 */
class Mageplace_Backup_Block_Adminhtml_Backup_Item_Js extends Mageplace_Backup_Block_Adminhtml_Backup_Js
{
    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('mpbackup/backup/item/js.phtml');
    }

    public function getBackupItem()
    {
        return Mage::getSingleton('mpbackup/backup_item')->getStaticData();
    }
}