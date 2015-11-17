<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

/**
 * Class Mageplace_Backup_Block_Adminhtml_Backup_Js_Step
 */
class Mageplace_Backup_Block_Adminhtml_Backup_Js_Step extends Mageplace_Backup_Block_Adminhtml_Backup_Js
{
    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('mpbackup/backup/js/step.phtml');
    }

    /**
     * @return Mageplace_Backup_Model_Backup_Step
     */
    public function getStepObject()
    {
        return Mage::getSingleton('mpbackup/backup_step');
    }
}