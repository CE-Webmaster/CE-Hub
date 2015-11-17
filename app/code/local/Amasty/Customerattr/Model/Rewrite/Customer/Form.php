<?php

/**
 * @author    Amasty Team
 * @copyright Amasty
 * @package   Amasty_Customerattr
 */
class Amasty_Customerattr_Model_Rewrite_Customer_Form
    extends Mage_Customer_Model_Form
{


     protected $_attributes = null;


    public function getAttributes()
    {
        $attributes = parent::getAttributes();
        $realAction = Mage::app()->getRequest()->getBeforeForwardInfo();
        $formCode = $this->getFormCode();
        if(strcmp($formCode,'adminhtml_customer_address')==0){
            return $attributes;
        }
        if(isset($realAction['action_name'])){
            $actionName = $realAction['action_name'];
        }else{
            $actionName = Mage::app()->getRequest()->getActionName();
        }
        //action_name index, edit, new
        $pageIsCustomerEdit = (strcmp(Mage::app()->getRequest()->getControllerName(),'customer')==0)
                            &&(strcmp($actionName,'edit')==0);

        $collection = Mage::getModel('customer/attribute')->getCollection();
        $alias = Mage::helper('amcustomerattr')->getProperAlias($collection->getSelect()->getPart('from'), 'eav_attribute');
        $collection->addFieldToFilter($alias . 'is_user_defined', 1);
        $collection->addFieldToFilter($alias . 'entity_type_id', Mage::getModel('eav/entity')->setType('customer')->getTypeId());
        
        $alias = Mage::helper('amcustomerattr')->getProperAlias($collection->getSelect()->getPart('from'), 'customer_eav_attribute');
        $collection->getSelect()->order($alias . 'sorting_order');

	    $temp = array();
        $entityType = Mage::getSingleton('eav/config')->getEntityType('customer');   
	    foreach ($attributes as $attribute) {
	        $temp[] = $attribute->getAttributeCode();
	    }
        //$attribute->getOnRgistration()
        foreach ($collection as $attribute)
        {
            $attribute = Mage::getModel('customer/attribute')->loadByCode($entityType, $attribute->getName());
            if ('selectgroup' == $attribute->getTypeInternal()) { // do not need to show `Customer Group Selector` attribute in the Backend
                continue;
            }
            // should not add attributes on the order create page in the backend
            if ('sales_order_create' != Mage::app()->getRequest()->getControllerName())
            {
                if ('customer_activated' != $attribute->getAttributeCode() && 'unlock_customer' != $attribute->getAttributeCode())
                {    
                    // filter attributes by store on the edit customer page in the backend
                    if ($pageIsCustomerEdit)
                    {      
                        $applicableStoreIds = explode(',', $attribute->getStoreIds());
                        // 0 means allowed on all store views
                        if (!in_array(0, $applicableStoreIds))
                        {    
                            if (!in_array(Mage::registry('current_customer')->getStoreId(), $applicableStoreIds) && 0 != Mage::registry('current_customer')->getStoreId())
                            { 
                                continue;
                            }
                        }
                    }
		            if (!in_array($attribute->getAttributeCode(), $temp)) {
                        $attributes[$attribute->getAttributeCode()] = $attribute;
                    } 
                }
           }
        }
        $this->_attributes = $attributes;
        return $attributes;
    }

    protected function _isAttributeOmitted($attribute)
    {
        $res = parent::_isAttributeOmitted($attribute);
        if (!Mage::app()->getStore()->isAdmin()
            && $attribute->getIsUserDefined()
            && $attribute->getEntityTypeId() == Mage::getModel('eav/entity')
                ->setType('customer')->getTypeId()
        ) {
            // will skip all user-defined (created with the extension) attributes, to avoid errors on checkout for registered customer
            $res = true;
        }
        $storeAttribute = $attribute->getStoreIds();
        if ($storeAttribute) {
            $customer = Mage::getModel('customer/customer');
            $customerId = Mage::app()->getRequest()->getParam('id');
            if ($customerId) {
                $customer->load($customerId);
                $storeId = $customer->getStoreId();
                if (is_numeric($storeAttribute)) {
                    if ($storeId != $storeAttribute) {
                        $res = true;
                    }
                } else {
                    $res = true;
                    $storeAttribute = explode(',', $storeAttribute);
                    foreach ($storeAttribute as $attr) {
                        if ($attr == $storeId) {
                            $res = false;
                            break;
                        }
                    }
                }
            }
        }
        return $res;
    }
}