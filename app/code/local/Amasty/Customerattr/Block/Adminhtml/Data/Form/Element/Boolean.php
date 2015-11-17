<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Varien
 * @package    Varien_Data
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Form select element
 *
 * @category    Varien
 * @package     Varien_Data
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Amasty_Customerattr_Block_Adminhtml_Data_Form_Element_Boolean
    extends Varien_Data_Form_Element_Select
{


    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        //   $this->_renderer = $this->getLayout()->createBlock("amcustomerattr/adminhtml_customer_attribute_grid_renderer_type");
    }

    public function getElementHtml()
    {
        $this->setValues(array('No', 'Yes'));
        return parent::getElementHtml();
    }

    public function getName()
    {
        $name = $this->getForm()->addSuffixToName(
            $this->getData('name'), 'amcustomerattr'
        );

        return $name;
    }


}
