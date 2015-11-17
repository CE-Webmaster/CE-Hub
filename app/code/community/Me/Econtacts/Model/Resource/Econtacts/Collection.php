<?php
/**
 * Class Me_Econtacts_Model_Resource_Econtacts_Collection
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Model_Resource_Econtacts_Collection
 */
class Me_Econtacts_Model_Resource_Econtacts_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('me_econtacts/econtacts');
    }
}
