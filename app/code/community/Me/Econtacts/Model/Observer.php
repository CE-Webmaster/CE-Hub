<?php
/**
 * Class Me_Econtacts_Model_Observer
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Model_Observer
 */
class Me_Econtacts_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Save captured post into database
     *
     * @param Varien_Event_Observer $observer observer
     * @throws Exception
     * @return void|boolean
     */
    public function saveContact(Varien_Event_Observer $observer)
    {
        $_helper = $this->_getHelper();

        if (!$_helper->isEnabled()) {
            return false;
        }

        $event = $observer->getEvent();
        $post = $event->getControllerAction()->getRequest()->getPost();

        if ($post) {

            try {
                $isNotValid = $_helper->checkErrors($post);

                if ($isNotValid) {
                    throw new Exception('Econtacts: form fields are invalid.');
                }

                $data = array(
                    'store_id' => Mage::app()->getStore()->getId()
                );

                $data = array_merge($data, $post);
                $data['comment'] = Mage::helper('core')->stripTags($data['comment'], null, false);

                if (!empty($data)) {
                    $eContactsModel = Mage::getModel('me_econtacts/econtacts');
                    $eContactsModel->addData($data);

                    $eContactsModel->save();
                }
            } catch (Mage_Core_Exception $e) {
                $_helper->setLog($e->getMessage());
            } catch (Exception $e) {
                $_helper->setLog($e->getMessage());
            }

        }
    }

    /**
     * Get extension helper
     *
     * @return Me_Econtacts_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('me_econtacts');
    }
}
