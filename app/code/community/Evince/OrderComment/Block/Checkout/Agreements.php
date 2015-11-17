<?php
/**
 * @category    Evince
 * @package     Evince_OrderComment
 * @copyright   Copyright (c) 2015 Evince Development 
 */
class Evince_OrderComment_Block_Checkout_Agreements extends Mage_Checkout_Block_Agreements
{
    protected function _toHtml()
    {
        $this->setTemplate('ordercomment/checkout/agreements.phtml');
        return parent::_toHtml();
    }
}