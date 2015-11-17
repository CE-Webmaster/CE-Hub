<?php
/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Renderer_Comment
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Renderer_Comment
 */
class Me_Econtacts_Block_Adminhtml_Econtacts_Renderer_Comment extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Truncate long comment in grid
     *
     * @param Varien_Object $row row
     * @return mixed|string
     */
    public function render(Varien_Object $row)
    {
        $value = $row->getData('comment');
        if ($value && strlen($value) > 300) {
            $value = Mage::helper('core/string')->truncate($value, 300);
        }

        return $value;
    }
}
