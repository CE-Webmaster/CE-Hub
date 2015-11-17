<?php

class Cminds_MultiUserAccounts_Block_Adminhtml_SubAccount_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('user_subaccount');
        $this->setUseAjax(true);
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Retrieve grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/subAccount/grid', array('_current' => true));
    }

    /**
     * Retrieve row url
     *
     * @return string
     */
    public function getRowUrl($item)
    {
        return $this->getUrl('*/subAccount/edit', array('id' => $item->getId()));
    }

    /**
     * Prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('cminds_multiuseraccounts/subAccount_collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'            => Mage::helper('cminds_multiuseraccounts')->__('ID'),
            'index'             => 'entity_id',
            'type'              => 'text'
        ));

        $this->addColumn('email', array(
            'header'            => Mage::helper('cminds_multiuseraccounts')->__('Email'),
            'index'             => 'email',
            'type'              => 'text'
        ));

        $this->addColumn('firstname', array(
            'header'            => Mage::helper('cminds_multiuseraccounts')->__('First Name'),
            'index'             => 'firstname',
            'type'              => 'text',
            'escape'            => true
        ));

        $this->addColumn('lastname', array(
            'header'            => Mage::helper('cminds_multiuseraccounts')->__('Last Name'),
            'index'             => 'lastname',
            'type'              => 'text',
            'escape'            => true
        ));

        $this->addColumn('permission', array(
            'header'            => Mage::helper('cminds_multiuseraccounts')->__('Permission'),
            'index'             => 'permission',
            'type'              => 'options',
            'options'           => Mage::getSingleton('cminds_multiuseraccounts/subAccount_permission')->getOptionArray()
        ));

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(array(
                    'caption'   => Mage::helper('catalog')->__('Edit'),
                    'url'       => array(
                        'base'=>'*/subAccount/edit'
                    ),
                    'field'   => 'id'
                )),
                'filter'    => false,
                'sortable'  => false,
            ));

        return parent::_prepareColumns();
    }
}
