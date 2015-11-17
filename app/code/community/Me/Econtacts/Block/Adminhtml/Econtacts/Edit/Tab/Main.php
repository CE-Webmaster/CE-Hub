<?php
/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Edit_Tab_Main
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * Class Me_Econtacts_Block_Adminhtml_Econtacts_Edit_Tab_Main
 */
class Me_Econtacts_Block_Adminhtml_Econtacts_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Load WYSIWYG on demand and prepare layout
     *
     * @return Me_Econtacts_Block_Adminhtml_Econtacts_Edit_Tab_Main
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        return $this;
    }

    /**
     * Prepare form elements for tab
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $_helper = Mage::helper('me_econtacts');
        $model = Mage::registry('contact_item');

        if (Mage::helper('me_econtacts/admin')->isActionAllowed('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('econtacts_main_');

        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array(
                'add_widgets' => 0,
                'tab_id' => $this->getTabId()
            )
        );

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array(
                'legend' => $_helper->__('Contact Info')
            )
        );

        if ($model->getId()) {
            $fieldset->addField(
                'econtacts_id',
                'hidden', array(
                    'name' => 'econtacts_id',
                )
            );
        }

        $fieldset->addField(
            'name',
            'text',
            array(
                'name' => 'name',
                'label' => $_helper->__('Name'),
                'title' => $_helper->__('Name'),
                'required' => true,
                'disabled' => $isElementDisabled
            )
        );

        $fieldset->addField(
            'email',
            'text',
            array(
                'name' => 'email',
                'label' => $_helper->__('Email'),
                'title' => $_helper->__('Email'),
                'required' => true,
                'disabled' => $isElementDisabled,
                'class' => 'validate-email'
            )
        );

        $fieldset->addField(
            'telephone',
            'text',
            array(
                'name' => 'telephone',
                'label' => $_helper->__('Telephone'),
                'title' => $_helper->__('Telephone'),
                'required' => false,
                'disabled' => $isElementDisabled
            )
        );

        $fieldset->addField(
            'comment',
            'textarea',
            array(
                'name' => 'comment',
                'style' => 'width:700px;height:200px;',
                'label' => $_helper->__('Comment'),
                'title' => $_helper->__('Comment'),
                'required' => true,
                'disabled' => $isElementDisabled
            )
        );


        $fieldset->addField(
            'answered',
            'select',
            array(
                'name' => 'answered',
                'label' => $_helper->__('Answered'),
                'title' => $_helper->__('Answered'),
                'options' => Mage::getSingleton('me_econtacts/econtacts')->getAnsweredOptionArray(),
                'required' => true,
                'disabled' => $isElementDisabled
            )
        );

        $fieldset->addField(
            'answer',
            'editor',
            array(
                'name' => 'answer',
                'style' => 'width:700px;height:300px;',
                'label' => $_helper->__('Answer'),
                'title' => $_helper->__('Answer'),
                'required' => true,
                'config' => $wysiwygConfig
            )
        );


        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('me_econtacts')->__('Contact Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('me_econtacts')->__('Contact Info');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
}
