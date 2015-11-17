<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Scroll
 */ 
class Amasty_Scroll_Model_Observer
{
    public function onListBlockHtmlAfter($observer)
    {
        $productListBlockT = Mage::app()->getLayout()->getBlockSingleton('catalog/product_list_toolbar');
        $class = get_class($productListBlockT);
        if (!($observer->getBlock() instanceof $class) || Mage::getStoreConfig('amscroll/general/loading') == 'none') {
            return;
        }

        $html = $observer->getTransport()->getHtml();
        $productToolbarBlock = $observer->getBlock();
        if($productToolbarBlock) {
            $addHtml = '<div id="am-pager-count" style="display: none !important;">'
                            . $productToolbarBlock->getLastPageNum() .
                        '</div>';
            $html .= $addHtml;
            $observer->getTransport()->setHtml($html);
        }
	}

    public function handleLayoutRender()
    {
        $isScroll = Mage::app()->getRequest()->getParam('is_scroll');
        if(!$isScroll) {
            return;
        }
        $layout = Mage::getSingleton('core/layout');
        if (!$layout)
            return;
            
        $isAJAX = Mage::app()->getRequest()->getParam('is_ajax', false);
        $isAJAX = $isAJAX && Mage::app()->getRequest()->isXmlHttpRequest();
        if (!$isAJAX)
            return;
            
        $layout->removeOutputBlock('root');    
        Mage::app()->getFrontController()->getResponse()->setHeader('content-type', 'application/json');
            
		$page = Mage::helper('amscroll')->findProductList($layout);
		if (!$page) {
			return;
		}

        $response = Mage::helper('core')->jsonEncode(
            array(
                'page' => $this->_removeAjaxParam($page->toHtml())
            )
        );

        Mage::app()->getResponse()->setBody($response)->sendResponse();
        exit;
    }
    
    protected function _removeAjaxParam($html)
    {
        $html = str_replace('is_ajax=1&amp;', '', $html);
        $html = str_replace('is_ajax=1&',     '', $html);
        $html = str_replace('?is_ajax=1',     '', $html);
        $html = str_replace('is_ajax=1',      '', $html);

        $html = str_replace('?___SID=U', '', $html);
        $html = str_replace('___SID=U',  '', $html);
        
        return mb_convert_encoding($html , 'UTF-8', mb_detect_encoding($html) );
    }
}