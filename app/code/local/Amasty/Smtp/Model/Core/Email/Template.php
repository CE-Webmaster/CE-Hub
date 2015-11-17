<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Smtp
 */
class Amasty_Smtp_Model_Core_Email_Template extends Amasty_Customerattr_Model_Rewrite_Core_Email_Template //Mage_Core_Model_Email_Template
{
    public function send($email, $name = null, array $variables = array())
    {
        if (!Mage::getStoreConfig('amsmtp/general/enable'))
        {
            return parent::send($email, $name, $variables);
        }

        Mage::helper('amsmtp')->debug('Ready to send e-mail at amsmtp/core_email_template::send()');

        if (!$this->isValidForSend()) {
            Mage::helper('amsmtp')->debug('E-mail not valid for send. Probably SMTP is disabled or template incorrect.');
            Mage::logException(new Exception('This letter cannot be sent.'));
            return false;
        }

        $emails = array_values((array)$email);
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);
        foreach ($emails as $key => $email) {
            if (!isset($names[$key])) {
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        $variables['email'] = reset($emails);
        $variables['name'] = reset($names);

        $mail = $this->getMail();

        foreach ($emails as $key => $email) {
            $mail->addTo($email, '=?utf-8?B?' . base64_encode($names[$key]) . '?=');
        }

        $this->setUseAbsoluteLinks(true);
        $text = $this->getProcessedTemplate($variables, true);

        $mail->setBodyHTML($text);

        $plainText = $this->html2text($text);
        $mail->setBodyText($plainText);


        $mail->setSubject('=?utf-8?B?' . base64_encode($this->getProcessedTemplateSubject($variables)) . '?=');
        $mail->setFrom($this->getSenderEmail(), $this->getSenderName());

        $logIds = array();
        foreach ($emails as $key => $email) {
            $logId = Mage::helper('amsmtp')->log(array(
                'subject'           => $this->getProcessedTemplateSubject($variables),
                'body'              => $text,
                'recipient_name'    => $names[$key],
                'recipient_email'   => $email,
                'template_code'     => $this->getTemplateId(),
                'status'            => Amasty_Smtp_Model_Log::STATUS_PENDING,
            ));
            $logIds[] = $logId;
        }

        try
        {
            $transportFacade = Mage::getModel('amsmtp/transport');

            if (!Mage::getStoreConfig('amsmtp/general/disable_delivery'))
            {
                $mail->send($transportFacade->getTransport());
            } else
            {
                Mage::helper('amsmtp')->debug('Actual delivery disabled under settings.');
            }

            foreach ($logIds as $logId)
            {
                Mage::helper('amsmtp')->logStatusUpdate($logId, Amasty_Smtp_Model_Log::STATUS_SENT);
            }

            Mage::helper('amsmtp')->debug('E-mail sent successfully at amsmtp/core_email_template::send().');
            $this->_mail = null;
        } catch (Exception $e)
        {
            Mage::logException($e);
            foreach ($logIds as $logId)
            {
                Mage::helper('amsmtp')->logStatusUpdate($logId, Amasty_Smtp_Model_Log::STATUS_FAILED);
            }
            Mage::helper('amsmtp')->debug('Error sending e-mail: ' . $e->getMessage());
            $this->_mail = null;
            return false;
        }

        return true;
    }

    public function html2text($text)
    {
        require_once Mage::getBaseDir('lib') . DS . 'Html2Text' . DS . 'Html2Text.php';

        $converter = new Html2Text($text);

        return $converter->get_text();
    }
}