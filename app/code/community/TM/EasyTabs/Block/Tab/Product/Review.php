<?php

class TM_EasyTabs_Block_Tab_Product_Review extends Mage_Review_Block_Product_View_List
{
    protected function _prepareLayout()
    {
        $reviewForm = $this->getLayout()->createBlock('review/form', 'product.review.form');
        if ($reviewForm) {
            $wrapper = $this->getLayout()
                ->createBlock('page/html_wrapper', 'product.review.form.fields.before');
            if ($wrapper) {
                $wrapper->setMayBeInvisible(1);
                $reviewForm->setChild('form_fields_before', $wrapper);
            }
            $this->setChild('review_form', $reviewForm);
        }
        return parent::_prepareLayout();
    }
     public function reviewscollection()
         {
            if(Mage::getSingleton('customer/session')->isLoggedIn())
            {

                $customer_id= Mage::getSingleTon('customer/session')->getId();
                $customer_reviews=Mage::getResourceModel('reviewsplus/reviewsplus')
                ->customerreviews($customer_id);
                return $customer_reviews;
            }  
        }
        
        public function ratingSummary()
        {
            $productid= $this->getProduct()->getId(); 
            $storeId = Mage::app()->getStore()->getId();
            list($summaryData['rating_summary'], $array,$string)=Mage::getResourceModel('reviewsplus/reviewsplus')
                ->ratings($productid, $storeId);
            return array($summaryData['rating_summary'], $array,$string);
        }
}
