<?php
/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Edit_Tabs
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Edit_Tabs
 */
class Me_Econtacts_Block_Adminhtml_Econtacts_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize tabs and define tabs block settings
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('econtacts_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('me_econtacts')->__('Contact Info'));
    }
}
