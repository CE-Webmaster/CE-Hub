<?php
/**
 * Class Me_Econtacts_Model_Econtacts
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Model_Econtacts
 */
class Me_Econtacts_Model_Econtacts extends Mage_Core_Model_Abstract
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('me_econtacts/econtacts');
    }

    /**
     * If object is new adds creation date
     *
     * @return Me_Econtacts_Model_Econtacts
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        if ($this->isObjectNew()) {
            $this->setData('created_at', Varien_Date::now());
        }
        return $this;
    }

    /**
     * Get options in "key-value" format for old-school 1.6 version
     *
     * @return array
     */
    public function getAnsweredOptionArray()
    {
        return array(
            0 => Mage::helper('me_econtacts')->__('No'),
            1 => Mage::helper('me_econtacts')->__('Yes'),
        );
    }
}
