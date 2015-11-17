<?php

/**
 * @author    Amasty Team
 * @copyright Copyright (c) 2008-2012 Amasty (http://www.amasty.com)
 * @package   Amasty_Customerattr
 *
 *
 *
 *
 */
class Amasty_Customerattr_Block_Adminhtml_Data_Form_Element_File
    extends Mage_Adminhtml_Block_Customer_Form_Element_File
{
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
    }

    public function getName()
    {
        $name = 'amcustomerattr[' . $this->getData('name') . ']';

        return $name;
    }
}
