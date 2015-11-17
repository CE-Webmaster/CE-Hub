<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Feed
 */

    class Amasty_Feed_Model_Attribute_Compound_Category extends Amasty_Feed_Model_Attribute_Compound_Abstract
    {
        function prepareCollection($collection){
            $collection->joinCategories();
        }
        
        function getCompoundData($productData){
            
            $ret = parent::getCompoundData($productData);
            $categoryId = $this->_getCategoryId($productData);
            
            if ($categoryId) {
                $categories = $this->getFeed()->getProductCategoriesLast();

                if (isset($categories[$categoryId]))
                    $ret = $categories[$categoryId];
            }
            
            return $ret;
            
        }
        
        function hasCondition(){
            return true;
        }
        
        function hasFilterCondition(){
            return true;
        }
        
        function validateFilterCondition($productData, $operator, $valueCode){
            return Amasty_Feed_Model_Field_Condition::compare($operator, $productData['category_id'], $valueCode);
        }
        
        function prepareCondition($collection, $operator, $condVal, &$attributesFields){
            $collection->joinCategories();
                
            $attributesFields[] = array(
                'attribute' => 'category_id', 
                $operator => $condVal
            );
        }
    }
?>