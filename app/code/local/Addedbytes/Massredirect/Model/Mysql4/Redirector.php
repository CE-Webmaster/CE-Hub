<?php
class Addedbytes_Massredirect_Model_Mysql4_Redirector extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('addedbytes_massredirect/massredirect', 'redirect_id');
    }

    /**
     * See if a URL exists in the imported file for this website.
     */
    public function loadByUrl($lookupUrl)
    {
        $websiteId = Mage::app()->getWebsite()->getId();
        $table = Mage::getSingleton('core/resource')->getTableName('massredirect/massredirect');
        $read = $this->_getReadAdapter();
        $website_where = $read->quoteInto("website_id = ?", $websiteId);
        $url_where = $read->quoteInto("old_url = ?", $lookupUrl);
        $select = $read
                    ->select()
                    ->from($table)
                    ->where($website_where)
                    ->where($url_where);
        $redirect_id = $read->fetchRow($select);
        return $redirect_id;
    }
}
