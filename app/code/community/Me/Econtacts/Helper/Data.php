<?php
/**
 * Class Me_Econtacts_Helper_Data
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Helper_Data
 */
class Me_Econtacts_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Path to store config if extension is enabled
     *
     * @var string
     */
    const XML_PATH_ENABLED = 'econtacts/config/enabled';

    /**
     * @var string
     */
    protected $_log = 'econtacts.log';

    /**
     * Check if extension enabled
     *
     * @param integer|string|Mage_Core_Model_Store $store store
     * @return boolean
     */
    public function isEnabled($store = null)
    {
        if ($this->isModuleOutputEnabled() && Mage::getStoreConfigFlag(self::XML_PATH_ENABLED, $store)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Write log information
     *
     * @param mixed $msg message
     * @return void
     */
    public function setLog($msg)
    {
        Mage::log($msg, null, $this->_log, true);
    }

    /**
     * Check if the captured post is valid
     *
     * @param array $post post
     * @return bool
     */
    public function checkErrors($post)
    {
        $error = false;

        if (!Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
            $error = true;
        }

        if (!Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
            $error = true;
        }

        if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
            $error = true;
        }

        if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
            $error = true;
        }

        return $error;
    }

    /**
     * Process content
     *
     * @param string $content content
     * @return string
     * @throws Exception
     */
    public function processContent($content)
    {
        $html = '';

        if ($content) {
            $helper = Mage::helper('cms');
            $processor = $helper->getPageTemplateProcessor();
            $html = $processor->filter($content);
        }

        return $html;
    }
}
