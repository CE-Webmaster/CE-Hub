<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */


class Mageplace_Hideprice_Adminhtml_HidepriceController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('catalog/hideprice')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Catalog'), Mage::helper('adminhtml')->__('Catalog'))
            ->_addBreadcrumb($this->__('Hideprice'), $this->__('Hideprice'))
            ->_title($this->__('Catalog'))
            ->_title($this->__('Hideprice'));

        return $this;
    }

    public function categoryAction()
    {
       $this->_initAction()
            ->_addBreadcrumb($this->__('Categories Settings'), $this->__('Categories Settings'))
            ->_title($this->__('Categories Settings'));

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();
    }

    public function categoriesJsonAction()
    {
        if ($categoryId = (int)$this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $categoryId);

            if (!$category = $this->_initCategory()) {
                return;
            }
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock('adminhtml/catalog_category_tree')
                    ->getTreeJson($category)
            );
        }
    }

    public function saveCategoryAction()
    {
        $this->_redirect('*/*/category');

        $categories = explode(',', $this->getRequest()->getPost('updateElement'));
        if(empty($categories)) {
            return $this->_getSession()->addError('Please select categories');
        }

        $counter = 0;
        $storeId = $this->getRequest()->getPost('store');
        foreach($categories as $id) {
            $id = (int)$id;
            try {
                $model =  Mage::getModel('mageplace_hideprice/hideprice')->loadByCategoryId($id);
                if (!$model->getId()) {
                    $model->setData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_CATEGORY_ID, $id);
                }

                $mode = $this->getRequest()->getPost('HP_mode');
                if (!Mage::app()->isSingleStoreMode()) {
                    $model->setData('store_id', $storeId);
                }

                $model->setData('delete', 0 == $mode);

                if ($mode > 0) {
                    $model->setData('mode', $mode);
                    $model->setData('exclude_children', $this->getRequest()->getPost('HP_children'));
                    $model->setData('groups', $this->getRequest()->getPost('HP_group'));
                    $model->setData('priority', $this->getRequest()->getPost('HP_priority'));
                }

                $model->save();

                $counter++;

            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) have been updated.', $counter));
    }

    public function productAction()
    {
        $this->_initAction()
            ->_addBreadcrumb($this->__('Products Settings'), $this->__('Products Settings'))
            ->_title($this->__('Products Settings'));

        $this->renderLayout();
    }

    public function productGridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody($this->getLayout()->createBlock('mageplace_hideprice/adminhtml_catalog_product_grid')->toHtml());
    }

    public function saveProductAction()
    {
        $this->_redirect('*/*/product');

        $products = $this->getRequest()->getPost('products');
        if(empty($products)) {
            return $this->_getSession()->addError('Please select products');
        }

        $counter = 0;
        $storeId = $this->getRequest()->getPost('store');
        foreach($products as $id) {
            $id = (int)$id;
            try {
                $model =  Mage::getModel('mageplace_hideprice/hideprice')->loadByProductId($id);
                if (!$model->getId()) {
                    $model->setData(Mageplace_Hideprice_Model_Mysql4_Hideprice::FIELD_PRODUCT_ID, $id);
                }

                $mode = $this->getRequest()->getPost('HP_mode');
                if (!Mage::app()->isSingleStoreMode()) {
                    $model->setData('store_id', $storeId);
                }

                $model->setData('delete', 0 == $mode);

                if ($mode > 0) {
                    $model->setData('mode', $mode);
                    $model->setData('groups', $this->getRequest()->getPost('HP_group'));
                }

                $model->save();

                $counter++;

            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) have been updated.', $counter));
    }

    /**
     * Initialize category object in registry
     *
     * @return Mage_Catalog_Model_Category
     */
    protected function _initCategory()
    {
        $categoryId = (int)$this->getRequest()->getParam('id', false);

        $storeId = (int)$this->getRequest()->getParam('store');

        $category = Mage::getModel('catalog/category');
        $category->setStoreId($storeId);

        if ($categoryId) {
            $category->load($categoryId);
            if ($storeId) {
                $rootId = Mage::app()->getStore($storeId)->getRootCategoryId();
                if (!in_array($rootId, $category->getPathIds())) {
                    $this->_redirect('*/*/', array('_current' => true, 'id' => null));
                    return false;
                }
            }
        }

        Mage::register('category', $category);
        Mage::register('current_category', $category);

        return $category;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('catalog/categories');
    }
}
