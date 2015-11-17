<?php

/**
 * @author    Amasty Team
 * @copyright Copyright (c) 2008-2012 Amasty (http://www.amasty.com)
 * @package   Amasty_Customerattr
 */
class Amasty_Customerattr_Block_Adminhtml_Data_Form_Element_Date
    extends Varien_Data_Form_Element_Date
{
    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
    }

    public function getName()
    {
        $name = $this->getForm()->addSuffixToName(
            $this->getData('name'), 'amcustomerattr'
        );

        return $name;
    }
}
