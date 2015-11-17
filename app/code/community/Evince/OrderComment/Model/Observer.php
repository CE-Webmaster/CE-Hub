<?php
/**
 * @category    Evince
 * @package     Evince_OrderComment
 * @copyright   Copyright (c) 2015 Evince Development 
 */
class Evince_OrderComment_Model_Observer extends Varien_Object
{
    public function saveOrderComment($observer)
    {
        $orderComment = Mage::app()->getRequest()->getPost('ordercomment');
        if (is_array($orderComment) && isset($orderComment['comment'])) {
            $comment = trim($orderComment['comment']);

            if (!empty($comment)) {
                $order = $observer->getEvent()->getOrder(); 
                $order->setCustomerComment($comment);
                $order->setCustomerNoteNotify(true);
                $order->setCustomerNote($comment);
            }
        }
    }
}