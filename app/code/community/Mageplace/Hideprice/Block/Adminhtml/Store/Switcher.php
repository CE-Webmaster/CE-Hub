<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


class Mageplace_Hideprice_Block_Adminhtml_Store_Switcher extends Mage_Adminhtml_Block_Store_Switcher
{
    /**
     * Set overriden params
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('hideprice/store/switcher.phtml');
        $this->setUseConfirm(false);
    }
}