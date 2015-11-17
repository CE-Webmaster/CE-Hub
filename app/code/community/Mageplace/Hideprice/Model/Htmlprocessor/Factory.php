<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */

class Mageplace_Hideprice_Model_Htmlprocessor_Factory
{

    const MODEL_CLASS_PROCESSOR = 'mageplace_hideprice/htmlprocessor';


    /**
     * @return false|Mageplace_Hideprice_Model_Htmlprocessor_Interface
     * @throws Exception
     */
    public function createProcessor()
    {
        $processorName = $this->_getHelper()->getProcessorName();
        $modelClass = self::MODEL_CLASS_PROCESSOR . '_' . $processorName;
        $model = Mage::getModel($modelClass);
        if($model === false){
            throw new Exception('Undefined processor model');
        }
        return $model;
    }

    /**
     * @return Mageplace_Hideprice_Helper_Abstract
     */
    private function _getHelper()
    {
        return Mage::helper('hideprice');
    }

}