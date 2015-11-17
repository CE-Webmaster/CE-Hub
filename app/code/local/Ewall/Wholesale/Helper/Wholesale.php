<?php

class Ewall_Wholesale_Helper_Wholesale extends Mage_Core_Helper_Abstract
{
	//storing wholesale product price into wholesale product table as per discount percentage for all prodcuts
	public function generalDiscount($data,$rule_id,$discount_amt)
	{
		$collection = Mage::getResourceModel('catalog/product_collection')
				->addStoreFilter($data['stores'])
				->addAttributeToSelect('*');
				
		$customer_models = Mage::getModel('wholesale/product');

		foreach ($collection as $item)
		{
			$org_price = $item->getData('price');
			
			//Get wholesale product model.
			$customer_model1 = $customer_models->getCollection();
			$customer_model1->addFieldToFilter('rule_id',array('eq' => $rule_id))->addFieldToFilter('product_id',array('eq' => $item->getEntityId()))->load();
			
			if(count($customer_model1)>0)
			{
				foreach($customer_model1 as $customer_model)
				{
					$customer_model->setCustomerGroupId($data['customer_group_id']);
					$customer_model->setWebsiteid($data['websiteid']);
					$customer_model->setStoreid($data['stores']);
					
					$discount_price = $org_price - round((($org_price/100)*$discount_amt), 3);
					$customer_model->setRulePrice($discount_price);
					
					$customer_model->save();
				}
			}
			else
			{
				$customer_models = Mage::getModel('wholesale/product');
				$customer_models->setRuleId($rule_id);
				$customer_models->setProductId($item->getEntityId());
				$customer_models->setCustomerGroupId($data['customer_group_id']);
				$customer_models->setWebsiteid($data['websiteid']);
				$customer_models->setStoreid($data['stores']);
				
				$discount_price = $org_price - round((($org_price/100)*$discount_amt), 3);
				$customer_models->setRulePrice($discount_price);
				
				$customer_models->save();
			}
		}
	}

	//storing wholesale product price into wholesale product table as per discount percentage based upon category discounts
	public function categoriesDiscount($data,$rule_id)
	{
		foreach($data['category_ids'] as $k=>$v)
		{
			if($v)
			{
				$category = Mage::getModel('catalog/category')->load($k);
				//$category->setIsAnchor(1);
				$productCollection = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('*')->addCategoryFilter($category);
				foreach ($productCollection as $item)
				{
					$org_price = $item->getData('price');
					$customer_models = Mage::getModel('wholesale/product');
					$customer_model1 = $customer_models->getCollection();
					$customer_model1->addFieldToFilter('rule_id',array('eq' => $rule_id))->addFieldToFilter('product_id',array('eq' => $item->getEntityId()))->load();
					if(count($customer_model1)>0)
					{
						foreach($customer_model1 as $customer_model)
						{
							
							$discount_price = $org_price - round((($org_price/100)*$v), 3);
							$customer_model->setRulePrice($discount_price);
							
							$customer_model->save();
						}
					}
				}
				if($category->hasChildren()) {
					foreach(explode(',',$category->getChildren()) as $cat) {
						$sub_category = Mage::getModel('catalog/category')->load($cat);
						$this->_check($sub_category,$data,$rule_id,$v);
					}
				}
			}
		}
	}

	//checking whether subcategories have discount percentage or not and applying rule by category discounts
	protected function _check($sub_category,$data,$rule_id,$v)
	{
		$productCollection = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('*')->addCategoryFilter($sub_category);
		foreach ($productCollection as $item)
		{
			$org_price = $item->getData('price');
			$customer_models = Mage::getModel('wholesale/product');
			$customer_model1 = $customer_models->getCollection();
			$customer_model1->addFieldToFilter('rule_id',array('eq' => $rule_id))->addFieldToFilter('product_id',array('eq' => $item->getEntityId()))->load();
			if(count($customer_model1)>0)
			{
				foreach($customer_model1 as $customer_model)
				{
					
					$discount_price = $org_price - round((($org_price/100)*$v), 3);
					$customer_model->setRulePrice($discount_price);
					
					$customer_model->save();
				}
			}
		}
	}

	//applying discounts based upon product discounts
	public function productsDiscount($data,$rule_id)
	{
		$products_discount = unserialize($data['in_featured_products']);
		foreach($products_discount as $k=>$v)
		{
			$customer_models = Mage::getModel('wholesale/product');
			$customer_model1 = $customer_models->getCollection();
			$customer_model1->addFieldToFilter('rule_id',array('eq' => $rule_id))->addFieldToFilter('product_id',array('eq' => $k))->load();
			$product = Mage::getModel('catalog/product')->load($k);
			$org_price = $product->getData('price');
			if(count($customer_model1)>0)
			{
				foreach($customer_model1 as $customer_model)
				{

					$discount_price = $org_price - round((($org_price/100)*$v['discount_product']), 3);
					$customer_model->setRulePrice($discount_price);

					$customer_model->save();
				}
			}
		}
	}

	//deleting rule by rule id
	public function deleteWholesalerRules($rule_id)
	{
		$customer_model1 = Mage::getModel('wholesale/product')->getCollection()->addFieldToFilter('rule_id',array('eq' => $rule_id))->load();//->delete();
		foreach($customer_model1 as $mod)
			$mod->delete();
	}
}

