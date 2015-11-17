<?php
/**
* Mageplace Callforprice
*
* @category      Mageplace
* @package       Mageplace_Callforprice
* @copyright     Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
* @license       http://www.mageplace.com/disclaimer.html
*/

class Mageplace_Callforprice_Model_Mysql4_Callforprice extends Mage_Core_Model_Mysql4_Abstract
{
    
    protected function _construct() 
	{
        $this->_init("mageplace_callforprice/callforprice", "id");
    }
}