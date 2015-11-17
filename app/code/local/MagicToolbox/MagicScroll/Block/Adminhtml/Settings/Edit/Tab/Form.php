<?php

class MagicToolbox_MagicScroll_Block_Adminhtml_Settings_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $blockId = preg_replace('/^magicscroll_|_settings_block$/is', '', $this->getNameInLayout());

        $helper = Mage::helper('magicscroll/params');

        $tool = Mage::registry('magicscroll_core_class');
        $optionsIds = Mage::registry('magicscroll_options_ids');

        if($tool === null) {

            //require_once(dirname(__FILE__).DS.'..'.DS.'..'.DS.'..'.DS.'..'.DS.'core'.DS.'magicscroll.module.core.class.php');
            require_once(BP . str_replace('/', DS, '/app/code/local/MagicToolbox/MagicScroll/core/magicscroll.module.core.class.php'));
            $tool = new MagicScrollModuleCoreClass();

            /*
            foreach($helper->getDefaultValues() as $block => $params) {
                foreach($params as $id => $value) {
                    $tool->params->setValue($id, $value, $block);
                }
            }
            */

            $optionsIds = array();
            $model = Mage::registry('magicscroll_model_data');
            $data = $model->getData();
            if($data['value']) {
                $_params = unserialize($data['value']);
                foreach($_params as $block => $params) {
                    if(is_array($params)) {
                        $optionsIds[$block] = array();
                        foreach($params  as $id => $value) {
                            $optionsIds[$block][$id] = true;
                            $tool->params->setValue($id, $value, $block);
                        }
                    }
                }
            }

            Mage::register('magicscroll_core_class', $tool);
            Mage::register('magicscroll_options_ids', $optionsIds);

        }

        $form = new Varien_Data_Form();
        //$form->setHtmlIdPrefix('_general');
        $this->setForm($form);

        $gId = 0;
        foreach($helper->getParamsMap($blockId) as $group => $ids) {
            $fieldset = $form->addFieldset($blockId.'_group_fieldset_'.$gId++, array('legend' => Mage::helper('magicscroll')->__($group), 'class' => 'magicscrollFieldset'));
            $fieldset->addType('magicscroll_radios', 'MagicToolbox_MagicScroll_Block_Adminhtml_Settings_Edit_Tab_Form_Element_Radios');
            foreach($ids as $id) {
                $config = array(
                    'label'     => Mage::helper('magicscroll')->__($tool->params->getLabel($id, $blockId)),
                    'name'      => 'magicscroll['.$blockId.']['.$id.']',
                    'note'      => '',
                    'value'     => $tool->params->getValue($id, $blockId),
                    //'class'     => 'required-entry',
                    //'required'  => true,
                );
                $description = $tool->params->getDescription($id, $blockId);
                if($description) {
                    $config['note'] = $description;
                }
                $type = $tool->params->getType($id, $blockId);
                $values = $tool->params->getValues($id, $blockId);
                if($type != 'array' && $tool->params->valuesExists($id, $blockId, false)) {
                    if(!empty($config['note'])) $config['note'] .= "<br />";
                    $config['note'] .= "(allowed values: ".implode(", ", $values).")";
                }
                switch($type) {
                    case 'num':
                        $type = 'text';
                    case 'text':
                        break;
                    case 'array':
                        //switch($tool->params->getSubType($id, $tool->params->generalProfile)) {
                        switch($tool->params->getSubType($id, $blockId)) {
                            case 'select':
                                if($id == 'template') {
                                    $type = 'select';
                                    break;
                                }
                            case 'radio':
                                //$type = 'radios';
                                $type = 'magicscroll_radios';
                                $config['style'] = 'margin-right: 5px;';
                                break;
                            default:
                                $type = 'text';
                        }
                        $config['values'] = array();
                        foreach($values as $v) {
                            $config['values'][] = array('value'=>$v, 'label'=>$v);
                        }
                        break;
                    default:
                        $type = 'text';
                }


                $fieldset->addField($blockId.'-'.$id, $type, $config);
            }
            if($blockId == 'customslideshowblock' && $group == 'General') {
                $fieldset->addType('magicscroll_gallery', 'MagicToolbox_MagicScroll_Block_Adminhtml_Settings_Edit_Tab_Form_Element_Gallery');
                $fieldset->addField('customslideshowblock_gallery', 'magicscroll_gallery', array(
                    'label'     => Mage::helper('magicscroll')->__('Slideshow gallery'),
                    'name'      => 'magicscroll['.$blockId.'][gallery]',
                ));
            }
        }

        return parent::_prepareForm();

    }

}