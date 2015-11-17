<?php

class Ewall_Wholesale_Block_Adminhtml_Wholesale_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct() {
        parent::__construct();

        $this->setId('inchoo_featured_products');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(false);
        $this->setDefaultFilter(array('product_ids'=>1));
    }
    
    //get grid url on selection
	public function getGridUrl()
	{
		return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/productgrid', array('_current'=>true));
	}
	
	//get selected products for which all discount applied
	protected function _getSelectedProducts()
	{
		$customers = array_keys($this->getSelectedProducts());
		return $customers;
	}
	
	//get selected products for which all discount applied
	public function getSelectedProducts()
	{
		$model = Mage::getModel('wholesale/wholesale')->load($this->getRequest()->getParam('id'));
		$datas = $model->getData();
		return unserialize($datas['products']);
	}
	
    public function getProduct() {
        return Mage::registry('product');
    }

	//get current store
    protected function _getStore() {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

	//add filter for collection
    protected function _addColumnFilterToCollection($column) {

        // Set custom filter for in product flag
		if ($column->getId() == 'product_ids') {
			$ids = $this->_getSelectedProducts();
			if (empty($ids)) {
				$ids = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$ids));
			} else {
				if($productIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$ids));
				}
			}
		} else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
    }

	//preparing collection
    protected function _prepareCollection() {
        $store = $this->_getStore();
        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('sku')
                ->addAttributeToSelect('type_id')
                ->addAttributeToFilter('visibility', array('nin' => array(1,3)));
        if ($store->getId()) {
            $collection->addStoreFilter($store);
            $collection->joinAttribute('custom_name', 'catalog_product/name', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('status', 'catalog_product/status', 'entity_id', null, 'inner', $store->getId());
            $collection->joinAttribute('visibility', 'catalog_product/visibility', 'entity_id', 1, 'inner', $store->getId());
            $collection->joinAttribute('price', 'catalog_product/price', 'entity_id', null, 'left', $store->getId());
        } else {
            $collection->addAttributeToSelect('price');
            $collection->addAttributeToSelect('status');
            $collection->addAttributeToSelect('visibility');
        }
        $this->setCollection($collection);
        parent::_prepareCollection();
        $this->getCollection()->addWebsiteNamesToResult();
        return $this;
    }

	//preparing columns for products
    protected function _prepareColumns()
    {
        $this->addColumn('product_ids', array(
            'header_css_class' => 'a-center',
            'type' => 'checkbox',
            'name' => 'product_ids',
            'values' => $this->_getSelectedProducts(),
            'align' => 'center',
            'index' => 'entity_id'
        ));

        $this->addColumn('entity_id', array(
            'header' => Mage::helper('catalog')->__('ID'),
            'sortable' => true,
            'width' => '60',
            'index' => 'entity_id'
        ));
        
        $this->addColumn('name', array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name'
        ));
        
        $this->addColumn('type',
            array(
                'header'=> Mage::helper('catalog')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ));
        
        $this->addColumn('sku', array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'width' => '140',
            'index' => 'sku'
        ));

        $this->addColumn('visibility',
            array(
                'header'=> Mage::helper('catalog')->__('Visibility'),
                'width' => '70px',
                'index' => 'visibility',
                'type'  => 'options',
                'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
        ));
        
        $store = $this->_getStore();
        $this->addColumn('price',
            array(
                'header'=> Mage::helper('catalog')->__('Price'),
                'type'  => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
                'index' => 'price',
        ));
        
        $this->addColumn('discount_product', array(
            'header'            => Mage::helper('catalog')->__('Discount in %'),
            'name'              => 'discount_product',
            'type'              => 'input',
            'width'             => 60,
            'validate_class'    => 'validate-number',
            'index'             => 'discount_product',
            'editable'          => true,
            'edit_only'         => true,
            'filter'			=> false,
            'sortable'			=> false,
        ));

        return parent::_prepareColumns();
    }

}
