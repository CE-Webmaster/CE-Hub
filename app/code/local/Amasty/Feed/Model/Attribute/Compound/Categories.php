<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Feed
 */

    class Amasty_Feed_Model_Attribute_Compound_Categories extends Amasty_Feed_Model_Attribute_Compound_Abstract
    {
        function prepareCollection($collection){
            $collection->joinCategories();
        }
        
        function getCompoundData($productData){
            
            $ret = parent::getCompoundData($productData);
           
            $categoryId = $this->_getCategoryId($productData);
                
            if ($categoryId) {
                $categories = $this->getFeed()->getProductCategories();

                if (isset($categories[$categoryId]))
                    $ret = $categories[$categoryId];
            }
            
            return $ret;
            
        }
    }
?>