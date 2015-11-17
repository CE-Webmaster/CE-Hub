<?php
/**
 * @category    Evince
 * @package     Evince_OrderComment
 * @copyright   Copyright (c) 2015 Evince Development 
 */
class Evince_OrderComment_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    protected $_ambiguousColumns = array(
        'status',
        'created_at',
    );

    protected function _getCollectionClass()
    {
        return 'ordercomment/order_grid_collection';
    }

    protected function _prepareColumns()
    {
        parent::_prepareColumns();

        $this->addColumn('ordercomment', array(
            'header'       => Mage::helper('ordercomment')->__('Order Comment'),
            'index'        => 'ordercomment',
            'filter_index' => 'ordercomment_table.comment',
        ));

        foreach ($this->_ambiguousColumns as $index) {
            if (isset($this->_columns[$index])) {
                $this->_columns[$index]->setFilterIndex('main_table.' . $index);
            }
        }

        return $this;
    }

    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();

        $modules = (array)Mage::getConfig()->getNode('modules')->children();
        if (isset($modules['EM_DeleteOrder']) && $modules['EM_DeleteOrder']->is('active')) {
            $this->getMassactionBlock()->addItem('delete_order', array(
               'label'=> Mage::helper('sales')->__('Delete order'),
               'url'  => $this->getUrl('*/sales_order/deleteorder'),
            ));
        }
        return $this;
    }
}
