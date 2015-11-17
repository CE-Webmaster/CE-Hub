<?php
/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts
 */
class Me_Econtacts_Block_Adminhtml_Econtacts extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_blockGroup = 'me_econtacts';
        $this->_controller = 'adminhtml_econtacts';
        $this->_headerText = Mage::helper('me_econtacts')->__('Manage Contacts');

        parent::__construct();

        $this->_removeButton('add');
    }
}
