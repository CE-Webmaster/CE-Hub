<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */

/**
 * Created by JetBrains PhpStorm.
 * User: stepantsevich_m
 * Date: 12.06.13
 * Time: 14:00
 * To change this template use File | Settings | File Templates.
 */

interface Mageplace_Hideprice_Model_Processor_Interface
{

    /**
     * @param $params
     * @return mixed
     */
    public function process($params);

    public function setHtmlProcessor($processor);

}