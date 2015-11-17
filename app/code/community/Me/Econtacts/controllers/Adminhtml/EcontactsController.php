<?php
/**
 * Class Me_Econtacts_Adminhtml_EcontactsController
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Adminhtml_EcontactsController
 */
class Me_Econtacts_Adminhtml_EcontactsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Path to store config recipient email
     *
     * @var string
     */
    const XML_PATH_EMAIL_RECIPIENT = 'contacts/email/recipient_email';

    /**
     * Path to store config sender email identity
     *
     * @var string
     */
    const XML_PATH_EMAIL_SENDER = 'contacts/email/sender_email_identity';

    /**
     * Path to store config sender email template
     *
     * @var string
     */
    const XML_PATH_EMAIL_TEMPLATE = 'contacts/email/email_template';

    /**
     * Path to store config extension sender email identity
     *
     * @var string
     */
    const XML_PATH_CUSTOM_EMAIL_SENDER = 'econtacts/email/sender_email_identity';

    /**
     * Path to store config extension sender email template
     *
     * @var string
     */
    const XML_PATH_CUSTOM_EMAIL_TEMPLATE = 'econtacts/email/email_template';

    /**
     * Init actions
     *
     * @return Me_Econtacts_Adminhtml_EcontactsController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('customer/econtacts')
            ->_addBreadcrumb(
                Mage::helper('me_econtacts')->__('Contacts'),
                Mage::helper('me_econtacts')->__('Contacts')
            )
            ->_addBreadcrumb(
                Mage::helper('me_econtacts')->__('Manage Contacts'),
                Mage::helper('me_econtacts')->__('Manage Contacts')
            );

        return $this;
    }

    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('Contacts'))
            ->_title($this->__('Manage Contacts'));

        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Edit contact
     *
     * @return void
     */
    public function editAction()
    {
        $this->_title($this->__('Contacts'))
            ->_title($this->__('Manage Contacts'));

        $model = Mage::getModel('me_econtacts/econtacts');

        $eContactsId = $this->getRequest()->getParam('id');
        if ($eContactsId) {
            $model->load($eContactsId);

            if (!$model->getId()) {
                $this->_getSession()->addError(
                    Mage::helper('me_econtacts')->__('Contact does not exist.')
                );
                return $this->_redirect('*/*/');
            }

            $this->_title(Mage::helper('me_econtacts')->__("Answer Contact from '%s'", $model->getName()));
            $breadCrumb = Mage::helper('me_econtacts')->__('Send Answer');

            $this->_initAction()->_addBreadcrumb($breadCrumb, $breadCrumb);
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        Mage::register('contact_item', $model);
        $this->renderLayout();
    }

    /**
     * Save action
     *
     * @return void
     */
    public function saveAction()
    {
        $redirectPath = '*/*';
        $redirectParams = array();

        $data = $this->getRequest()->getPost();
        if ($data) {

            if ($this->getRequest()->getParam('send')) {
                $this->_forward('send');
                return;
            }

            $model = Mage::getModel('me_econtacts/econtacts');

            $eContactsId = $this->getRequest()->getParam('econtacts_id');
            if ($eContactsId) {
                $model->load($eContactsId);
            }

            $model->addData($data);

            try {
                $hasError = false;

                $model->save();

                $this->_getSession()->addSuccess(
                    Mage::helper('me_econtacts')->__('Contact has been saved.')
                );

                if ($this->getRequest()->getParam('back')) {
                    $redirectPath = '*/*/edit';
                    $redirectParams = array('id' => $model->getId());
                }
            } catch (Mage_Core_Exception $e) {
                $hasError = true;
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $hasError = true;
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('me_econtacts')->__('An error occurred while saving contact.')
                );
            }

            if ($hasError) {
                $this->_getSession()->setFormData($data);
                $redirectPath = '*/*/edit';
                $redirectParams = array('id' => $this->getRequest()->getParam('id'));
            }
        }

        $this->_redirect($redirectPath, $redirectParams);
    }

    /**
     * Send action
     *
     * @return void
     */
    public function sendAction()
    {
        $redirectPath = '*/*';
        $redirectParams = array();

        $data = $this->getRequest()->getPost();
        if ($data) {
            $model = Mage::getModel('me_econtacts/econtacts');

            $eContactsId = $this->getRequest()->getParam('econtacts_id');
            if ($eContactsId) {
                $model->load($eContactsId);
            } else {
                Mage::throwException(Mage::helper('me_econtacts')->__('Invalid contact data.'));
            }

            try {
                $hasError = false;

                if ($this->_sendAnswer($data, $model->getStoreId())) {
                    $model->setAnswered(1);
                    $model->setAnswer($data['answer']);
                    $model->save();
                }

                $this->_getSession()->addSuccess(
                    Mage::helper('me_econtacts')->__('Answer was successfully sent.')
                );

            } catch (Mage_Core_Exception $e) {
                $hasError = true;
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                $hasError = true;
                $this->_getSession()->addException(
                    $e,
                    Mage::helper('me_econtacts')->__('An error occurred while sending answer.')
                );
            }

            if ($hasError) {
                $this->_getSession()->setFormData($data);
                $redirectPath = '*/*/edit';
                $redirectParams = array('id' => $this->getRequest()->getParam('id'));
            }
        }

        $this->_redirect($redirectPath, $redirectParams);
    }

    /**
     * Delete action
     *
     * @return void
     */
    public function deleteAction()
    {
        $eContactsId = $this->getRequest()->getParam('id');
        if ($eContactsId) {
            try {

                $model = Mage::getModel('me_econtacts/econtacts');
                $model->load($eContactsId);
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('me_econtacts')->__('Unable to find contact.'));
                }
                $model->delete();

                $this->_getSession()->addSuccess(
                    Mage::helper('me_econtacts')->__('The contact has been deleted.')
                );

            } catch (Mage_Core_Exception $e) {

                $this->_getSession()->addError($e->getMessage());

            } catch (Exception $e) {

                $this->_getSession()->addException(
                    $e,
                    Mage::helper('me_econtacts')->__('An error occurred while deleting the contact.')
                );

            }
        }

        $this->_redirect('*/*/');
    }

    /**
     * Mass Delete action
     *
     * @return void
     */
    public function massDeleteAction()
    {
        $eContactsIds = $this->getRequest()->getParam('econtacts');
        if (!is_array($eContactsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($eContactsIds as $eContactsId) {
                    $eContacts = Mage::getModel('me_econtacts/econtacts')->load($eContactsId);
                    if (!is_null($eContacts) && $eContacts->getId()) {
                        $eContacts->delete();
                    }
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d contact(s) were successfully deleted',
                        count($eContactsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Mass Send action
     *
     * @return void
     * @throws Exception
     */
    public function massSendAction()
    {
        $eContactsIds = $this->getRequest()->getParam('econtacts');

        if (!is_array($eContactsIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($eContactsIds as $eContactsId) {

                    $eContacts = Mage::getModel('me_econtacts/econtacts')->load($eContactsId);

                    if (!is_null($eContacts) && $eContacts->getId()) {

                        $sendData = $eContacts->getData();
                        $sendData['comment'] = Mage::helper('core')->stripTags($sendData['comment'], null, false);

                        $sendObject = new Varien_Object();
                        $sendObject->setData($sendData);

                        $translate = Mage::getSingleton('core/translate');
                        $translate->setTranslateInline(false);

                        $mailTemplate = Mage::getModel('core/email_template');
                        $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                            ->setReplyTo($sendData['email'])
                            ->sendTransactional(
                                Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                                Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                                Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                                null,
                                array('data' => $sendObject)
                            );

                        if (!$mailTemplate->getSentSuccess()) {
                            throw new Exception();
                        }

                        $translate->setTranslateInline(true);

                    }

                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d contact(s) were successfully sent',
                        count($eContactsIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * Send answer
     *
     * @param array $data    data
     * @param int   $storeId store id
     * @return bool
     * @throws Exception
     */
    protected function _sendAnswer($data, $storeId = 0)
    {
        if ($data) {

            $translate = Mage::getSingleton('core/translate');
            $translate->setTranslateInline(false);

            $postObject = new Varien_Object();
            $data['answer'] = Mage::helper('me_econtacts')->processContent($data['answer']);
            $postObject->setData($data);

            $mailTemplate = Mage::getModel('core/email_template');
            $mailTemplate->setDesignConfig(array('area' => 'frontend', 'store' => $storeId))
                ->setReplyTo(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT))
                ->sendTransactional(
                    Mage::getStoreConfig(self::XML_PATH_CUSTOM_EMAIL_TEMPLATE),
                    Mage::getStoreConfig(self::XML_PATH_CUSTOM_EMAIL_SENDER),
                    $data['email'],
                    null,
                    array('data' => $postObject)
                );

            if (!$mailTemplate->getSentSuccess()) {
                throw new Exception();
            }

            $translate->setTranslateInline(true);

            return true;

        }

        return false;
    }

    /**
     * Check the permission to run it
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'save':
                return Mage::getSingleton('admin/session')->isAllowed('customer/econtacts/save');
                break;
            case 'delete':
                return Mage::getSingleton('admin/session')->isAllowed('customer/econtacts/delete');
                break;
            default:
                return Mage::getSingleton('admin/session')->isAllowed('customer/econtacts');
                break;
        }
    }

    /**
     * Grid ajax action
     *
     * @return void
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}
