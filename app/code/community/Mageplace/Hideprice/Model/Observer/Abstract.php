<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


abstract class Mageplace_Hideprice_Model_Observer_Abstract
{
    abstract public function getCatalogObject();

    abstract public function load($id);

    abstract public function getIdFieldName();

    public function saveTabData()
    {
        try {
            $object = $this->getCatalogObject();
            if (!is_object($object) || ($id = (int)$object->getId()) < 1) {
                return;
            }

            $model = $this->load($id);
            if (!$model->getId()) {
                $model->setData($this->getIdFieldName(), $id);
            }

            $mode = $this->_getRequest()->getPost('HP_mode');
            if (!Mage::app()->isSingleStoreMode()) {
                $model->setData('store_id', $object->getStoreId());
            }

            $model->setData('delete', 0 == $mode);

            if ($mode > 0) {
                $model->setData('mode', $mode);
                $model->setData('exclude_children', $this->_getRequest()->getPost('HP_children'));
                $model->setData('groups', $this->_getRequest()->getPost('HP_group'));
            }

            $model->save();

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * @return Mageplace_Hideprice_Model_Hideprice
     */
    protected function _getModel()
    {
        return Mage::getModel('mageplace_hideprice/hideprice');
    }

    /**
     * Return app request
     * @return Mage_Core_Controller_Request_Http
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }

}