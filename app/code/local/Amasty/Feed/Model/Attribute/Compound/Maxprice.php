<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Feed
 */

    class Amasty_Feed_Model_Attribute_Compound_Maxprice extends Amasty_Feed_Model_Attribute_Compound_Abstract
    {
        function prepareCollection($collection){
            $collection->joinPrice();
        }
        
        function getCompoundData($productData){
            return $productData['max_price'];
        }
    }
?>