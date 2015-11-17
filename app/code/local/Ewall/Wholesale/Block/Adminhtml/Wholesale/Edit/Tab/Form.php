<?php

class Ewall_Wholesale_Block_Adminhtml_Wholesale_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	//preparing form
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('wholesale_form', array('legend'=>Mage::helper('wholesale')->__('General information')));
     
      $fieldset->addField('rulename', 'text', array(
          'label'     => Mage::helper('wholesale')->__('Rule Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'rulename',
      ));
      
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('wholesale')->__('Status'),
          'name'      => 'status',
          'required'  => true,
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('wholesale')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('wholesale')->__('Disabled'),
              ),
          ),
      ));
        

        $website =  $fieldset->addField('websiteid', 'select', array(
                'name'      => 'websiteid',
                'label'     => Mage::helper('wholesale')->__('Websites'),
                'title'     => Mage::helper('wholesale')->__('Websites'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_config_source_website')->toOptionArray(),
                'onchange' => 'getstore(this)',
                'required'  => true,
            ));

		$data_store1 = Mage::registry('wholesale_data')->getData();
		$website_id = $data_store1['websiteid'];
        if ($website_id == '') 
			$website_id =1;
			$store_model = Mage::getModel('core/store');
			$stores = $store_model->getCollection()->addWebsiteFilter($website_id);
			$store_list[0] = 'All Stores';
			foreach ($stores as $_store) {
			$store_list[$_store->getId()] = $_store->getName();
			}
        
	   $fieldset->addField('storeid', 'select', array(
            'name'  => 'storeid',
            'label'     => 'Store',
            'values'    =>  $store_list,
            'required'  => true,
            'onchange' => 'setstore(this)',
        ));
  
        /*
         * Add Ajax to the website select box html output
         */
         $data_store = Mage::registry('wholesale_data')->getData();
         $id = $data_store['wholesale_id'];
        $website->setAfterElementHtml("<script type=\"text/javascript\">
            function getstore(selectElement){
                var reloadurl = '". $this
                 ->getUrl('wholesale/adminhtml_wholesale/store') . "websiteid/' + selectElement.value;
                new Ajax.Request(reloadurl, {
                    method: 'get',
                    onLoading: function (storeidform) {
                        $('storeid').update('Searching...');
                    },
                    onComplete: function(storeidform) {
                        $('storeid').update(storeidform.responseText);
                    }
                });
            }
            function setstore(selectElement){
                var reloadurl = '". $this
                 ->getUrl('wholesale/adminhtml_wholesale/catstore') . "storeid/' + selectElement.value + '/wholesaleid/".$id."';
                new Ajax.Request(reloadurl, {
                    method: 'get',
                    onLoading: function (storeidform) {
                        $('product-categories').update('Searching...');
                    },
                    onComplete: function(storeidform) {
                        $('product-categories').update(storeidform.responseText);
                    }
                });
            }
        </script>");
			
	    $customerGroups = Mage::getResourceModel('customer/group_collection')
            ->load()->toOptionArray();

        $found = false;
        foreach ($customerGroups as $group) {
            if ($group['value']==0) {
                $found = true;
            }
        }
        if (!$found) {
            array_unshift($customerGroups, array('value'=>0, 'label'=>Mage::helper('catalogrule')->__('NOT LOGGED IN')));
        }

        $fieldset->addField('customer_group_id', 'multiselect', array(
            'name'      => 'customer_group_id[]',
            'label'     => Mage::helper('catalogrule')->__('Customer Groups'),
            'title'     => Mage::helper('catalogrule')->__('Customer Groups'),
            'required'  => true,
            'values'    => $customerGroups,
        ));


      $fieldset->addField('discount', 'text', array(
          'label'     => Mage::helper('wholesale')->__('Discount in %'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'discount',
      ));
      
      $fieldset->addField('priority', 'text', array(
          'label'     => Mage::helper('wholesale')->__('Priority'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'priority',
      ));
			
     
      if ( Mage::getSingleton('adminhtml/session')->getBannerSliderData() )
      {
          $data = Mage::getSingleton('adminhtml/session')->getBannerSliderData();
          Mage::getSingleton('adminhtml/session')->setBannerSliderData(null);
      } elseif ( Mage::registry('wholesale_data') ) {
          $data = Mage::registry('wholesale_data')->getData();
      }
	  $data['store_id'] = explode(',',$data['stores']);
	  $form->setValues($data);
	  
      return parent::_prepareForm();
  }
 
  
}
