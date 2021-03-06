<?php
/*
 * Developer: Michael Jacky
* Team site: http://cmsideas.net/
* Support: http://support.cmsideas.net/
*
*/
class Glace_Productlabels_Model_Resource_Css_Collection extends Mage_Catalog_Model_Resource_Collection_Abstract
{
	/**
     * Css limitation filters
     * Allowed filters
     *  store_id                int;
     *
     * @var array
     */
    protected $_productLimitationFilters     = array();
	
	/**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('productlabels/css');
    }
	
	/**
     * Add store availability filter. Include availability product
     * for store website
     *
     * @param mixed $store
     * @return Glace_Blog_Model_Resource_Post_Collection
     */
    public function addStoreFilter($store = null)
    {
        if ($store === null) {
            $store = $this->getStoreId();
        }
        $store = Mage::app()->getStore($store);

        if (!$store->isAdmin()) {
            $this->setStoreId($store);
            $this->_productLimitationFilters['store_id'] = $store->getId();
            $this->_applyProductLimitations();
        }

        return $this;
    }
}
?>