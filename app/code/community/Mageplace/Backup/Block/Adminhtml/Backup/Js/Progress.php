<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

/**
 * Class Mageplace_Backup_Block_Adminhtml_Backup_Js_Progress
 */
class Mageplace_Backup_Block_Adminhtml_Backup_Js_Progress extends Mageplace_Backup_Block_Adminhtml_Backup_Js
{
    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('mpbackup/backup/js/progress.phtml');
    }

    /**
     * @return Mageplace_Backup_Model_Backup_Progress
     */
    public function getProgressObject()
    {
        return Mage::getSingleton('mpbackup/backup_progress');
    }

    /**
     * @return Mageplace_Backup_Model_Backup_Progress_Item
     */
    public function getProgressItemObject()
    {
        return Mage::getSingleton('mpbackup/backup_progress_item');
    }
}