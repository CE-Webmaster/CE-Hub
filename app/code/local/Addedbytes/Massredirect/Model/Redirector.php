<?php

class Addedbytes_Massredirect_Model_Redirector extends Mage_Core_Model_Abstract
{

    /**
     * This function does the work on the client side. The observer runs on
     * the predispatch event. It checks to see if the page was not found by
     * Magento. If the page wasn't found, it checks to see if the URL matches
     * an imported old URL.
     */
    public function observe($observer)
    {

        // Check module is enabled
        $isEnabled = Mage::getStoreConfig('massredirect/massredirect_options/massredirect_active');
        if ($isEnabled != 1) {
            return true;
        }

        // Get action name
        $request = $observer['controller_action']->getRequest();
        $actionName = $request->getActionName();

        // Only run for page not found
        if ($actionName == 'noRoute') {

            // Get alias of requested page, and clean up
            $oldUrl = Mage::helper('addedbytesmassredirect')->cleanUrl($request->getRequestUri());

            // Try to find a match for the URL by parts
            $urlParts = explode('/', $oldUrl);

            $urlByParts = '';
            for ($i = 0, $max = count($urlParts); $i < $max; $i++) {
                $urlByParts = implode('/', $urlParts);

                $result = Mage::getResourceModel('massredirect_import/redirector')->loadByUrl($urlByParts);
                if ($result) {
                    break;
                }

                // Drop the first segment of the URL and try again
                array_shift($urlParts);
            }

            if ($result) {

                // We have a matching result. See what type it is and handle appropriately.
                if (trim($result['sku']) != '') {
                    $newProductSlug = $this->getProductUrl($result['sku']);
                    if ($newProductSlug) {
                        $newUrl = '/' . $newProductSlug;
                        $this->redirectToUrl($newUrl);
                    }
                }

                // If still here, there's no matching SKU (either not entered
                // or not found). Check for a new URL instead of the SKU.
                if (trim($result['new_url']) != '') {
                    if (substr($result['new_url'], 0, 1) == '/') {
                        // If we have a leading slash, treat as a simple redirect
                        $this->redirectToUrl($result['new_url']);
                    } else {
                        // If here, this might be a full URL, or it might be a relative URL.
                        // Validate as URL and proceed accordingly.
                        if (filter_var($result['new_url'], FILTER_VALIDATE_URL)) {
                            $this->redirectToUrl($result['new_url']);
                        } else {
                            $this->redirectToUrl('/' . $result['new_url']);
                        }
                    }
                }

                // Still here? We found a matching redirect but couldn't
                // send the user onwards.
                Mage::log('Redirect found for URL: "' . $oldUrl . '", but could not be executed.', Zend_Log::INFO, 'addedbytes.log');
            } else {
                Mage::log('No redirect found for URL: "' . $oldUrl . '"', Zend_Log::INFO, 'addedbytes.log');
            }

            // We could try partial matching, or look at using soundex to find similar-sounding
            // products in future.

        }

        return true;
    }

    /**
     * Given a SKU, fetch the product URL.
     */
    public function getProductUrl($sku)
    {
        // Try to load product
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
        if (($product) && (get_class($product) == 'Mage_Catalog_Model_Product')) {
            return $product->getUrlPath();
        } else {
            $message = 'Failed to load SKU: "' . $sku . '".';
            if (!$product) {
                $message .= ' No matching SKU.';
            } else {
                $message .= ' Object of wrong type (expected Mage_Catalog_Model_Product, was "' . get_class($product) . '").';
            }
            Mage::log($message, Zend_Log::INFO, 'addedbytes.log');

            return false;
        }
    }

    /**
     * Redirect user to given URL, with a 301 header.
     */
    public function redirectToUrl($newUrl)
    {
        $response = Mage::app()->getResponse();
        $response->setRedirect($newUrl, 301);
        $response->sendResponse();
        exit;
    }
}
