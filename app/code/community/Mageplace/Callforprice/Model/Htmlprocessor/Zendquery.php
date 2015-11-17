<?php
/**
 * Mageplace Callforprice
 *
 * @category      Mageplace
 * @package       Mageplace_Callforprice
 * @copyright     Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license       http://www.mageplace.com/disclaimer.html
 */


class Mageplace_Callforprice_Model_Htmlprocessor_Zendquery
    implements Mageplace_Callforprice_Model_Htmlprocessor_Interface
{

    protected $_processorName = 'mageplace_callforprice/htmlprocessor_zendquery';

    private $_domObject = null;
    private $_lastResult = null;

    /**
     * @return Zend_Dom_Query
     * @throws Exception
     */
    protected function _getDomObject()
    {
        if(is_null($this->_domObject)){
            throw new Exception('Dom not loaded');
        }
        return $this->_domObject;
    }

    public function load($html)
    {
        $this->_domObject = new Zend_Dom_Query();
        $this->_domObject->setDocumentHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    }


    public function replace($selector, $replacement, $positions = null)
    {
        $this->_reloadDocument();

        $result = $this->_query($selector);
        if(count($result) === 0){
            return false;
        }

        foreach($result as $i => $element){

            if(is_array($positions) && !in_array($i, $positions)){
                continue;
            }

            /** @var $element DOMElement */
            $replacementElement = $element->ownerDocument
                ->createDocumentFragment();
            $replacementElement->appendXML($replacement);
            $element->parentNode->replaceChild($replacementElement, $element);
        }

        return true;
    }

    public function remove($selector)
    {
        $this->_reloadDocument();

        $result = $this->_query($selector);
        if(count($result) === 0){
            return false;
        }
        foreach($result as $element){
            /** @var $element DOMElement */
            $element->parentNode->removeChild($element);
        }

        return true;
    }

    public function removeInner($selector)
    {
        $this->_reloadDocument();

        $result = $this->_query($selector);
        if(count($result) === 0){
            return false;
        }
        foreach($result as $element){
            /** @var $element DOMElement */
            while ($element->hasChildNodes()){
                $element->removeChild($element->childNodes->item(0));
            }
        }

        return true;
    }


    public function process($processorName, $params)
    {
        $modelClass = $this->_processorName . '_' . $processorName;
        /** @var $model Mageplace_Hideprice_Model_Processor_Interface */
        $model = Mage::getModel($modelClass);
        if($model === false){
            throw new Exception('Undefined processor model');
        }

        $model->setHtmlProcessor($this);
        return $model->process($params);
    }

    public function getHtml()
    {
        if(!$this->_lastResult){
            return $this->_getDomObject()->getDocument();
        }
        /** @var $document DOMDocument */
        $document = $this->_lastResult->getDocument();
        $fragment = preg_replace('/^<!DOCTYPE.+?>/', '',
            str_replace( array('<html>', '</html>', '<body>', '</body>', '<head>', '</head>'), array('', '', '', ''), $document->saveHTML())
        );

        return $fragment;
    }


    protected function _reloadDocument()
    {
        if($this->_lastResult){
            $this->load($this->getHtml());
        }
        return $this;
    }

    /**
     * @param $query
     * @return Zend_Dom_Query_Result
     */
    protected function _query($query)
    {
        $this->_lastResult = $this->query($query);
        return $this->_lastResult;
    }

    public function query($query)
    {
        return $this->_getDomObject()->query($query);
    }


}
