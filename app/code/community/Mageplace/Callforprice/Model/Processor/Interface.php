<?php
/**
 * Mageplace Callforprice
 *
 * @category      Mageplace
 * @package       Mageplace_Callforprice
 * @copyright     Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license       http://www.mageplace.com/disclaimer.html
 */

interface Mageplace_Callforprice_Model_Processor_Interface
{

    /**
     * @param $params
     * @return mixed
     */
    public function process($params);

    public function setHtmlProcessor($processor);

}