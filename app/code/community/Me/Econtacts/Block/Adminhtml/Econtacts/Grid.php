<?php
/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Grid
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Grid
 */
class Me_Econtacts_Block_Adminhtml_Econtacts_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Init Grid default properties
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('econtactsGrid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare collection for Grid
     *
     * @return Me_Econtacts_Block_Adminhtml_Econtacts_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('me_econtacts/econtacts')->getResourceCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare Grid columns
     *
     * @return Mage_Adminhtml_Block_Catalog_Search_Grid
     */
    protected function _prepareColumns()
    {
        $_helper = Mage::helper('me_econtacts');

        $this->addColumn(
            'econtacts_id',
            array(
                'header' => $_helper->__('ID'),
                'width' => '50px',
                'index' => 'econtacts_id',
            )
        );

        $this->addColumn(
            'name',
            array(
                'header' => $_helper->__('Name'),
                'index' => 'name',
            )
        );

        $this->addColumn(
            'email',
            array(
                'header' => $_helper->__('Email'),
                'index' => 'email',
            )
        );

        $this->addColumn(
            'telephone',
            array(
                'header' => $_helper->__('Telephone'),
                'index' => 'telephone',
            )
        );

        $this->addColumn(
            'comment',
            array(
                'header' => $_helper->__('Comment'),
                'index' => 'comment',
                'renderer' => 'Me_Econtacts_Block_Adminhtml_Econtacts_Renderer_Comment'
            )
        );

        $this->addColumn(
            'answered',
            array(
                'header' => $_helper->__('Answered'),
                'index' => 'answered',
                'type' => 'options',
                'options' => Mage::getSingleton('me_econtacts/econtacts')->getAnsweredOptionArray()
            )
        );

        /**
         * Check if multi store
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn(
                'store_id',
                array(
                    'header' => $_helper->__('Store View'),
                    'index' => 'store_id',
                    'type' => 'store',
                    'store_all' => true,
                    'store_view' => true,
                    'sortable' => false,
                    'filter_condition_callback' => array($this, '_filterStoreCondition'),
                )
            );
        }

        $this->addColumn(
            'created_at',
            array(
                'header' => $_helper->__('Created'),
                'sortable' => true,
                'width' => '170px',
                'index' => 'created_at',
                'type' => 'datetime',
            )
        );

        $this->addColumn(
            'action',
            array(
                'header' => $_helper->__('Action'),
                'width' => '100px',
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(array(
                    'caption' => $_helper->__('Answer'),
                    'url' => array('base' => '*/*/edit'),
                    'field' => 'id'
                )),
                'filter' => false,
                'sortable' => false,
                'index' => 'econtacts',
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * Delete and Re-send mass actions
     *
     * @return $this|Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('econtacts_id');
        $this->getMassactionBlock()->setFormFieldName('econtacts');

        $this->getMassactionBlock()->addItem(
            'delete', array(
                'label' => Mage::helper('me_econtacts')->__('Delete'),
                'url' => $this->getUrl('*/*/massDelete'),
                'confirm' => Mage::helper('me_econtacts')->__('Are you sure?')
            )
        );

        $this->getMassactionBlock()->addItem(
            'send', array(
                'label' => Mage::helper('me_econtacts')->__('Re-send Original'),
                'url' => $this->getUrl('*/*/massSend'),
                'confirm' => Mage::helper('me_econtacts')->__('Are you sure?')
            )
        );

        return $this;
    }

    /**
     * Return row URL for js event handlers, removed
     *
     * @param object $row row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * Grid url getter
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * Store filter for grid
     *
     * @param Me_Econtacts_Model_Resource_Econtacts_Collection $collection collection
     * @param object                                           $column     column
     * @return void
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addFieldToFilter('store_id', array('in' => array(0, $value)));
    }
}
