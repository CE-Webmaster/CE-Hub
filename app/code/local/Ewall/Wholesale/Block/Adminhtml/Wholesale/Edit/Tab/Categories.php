<?php

class Ewall_Wholesale_Block_Adminhtml_Wholesale_Edit_Tab_Categories extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
	protected $_categoryIds;
    protected $_selectedNodes = null;

    public function __construct()
    {
        parent::__construct();
		$this->_withProductCount = false;
        $this->setTemplate('wholesale/categories.phtml');
    }

	//getting store ID's as array
	public function getAllstores()
	{
		$allStores = Mage::app()->getStores();
		foreach($allStores as $store)
			$arr[$store->getId()] = $store->getRootCategoryId();
		return $arr;
	}

	//getting categories HTML from ajax request
	public function  getCategoriesHtml($categories,$level,$storeId=1,$wholesaleid=null)
	{
		$array = '<ul class="level'.$level;
		if($level!=1) $array .= ' childrens ';
		$array .= '" style="padding1-left:'.(($level+0.5)*4).'px;" >';
		$len = $categories->count()."<br/>";
		$i = 1;
		foreach($categories as $category)
		{
			$cat = Mage::getModel('catalog/category')->setStoreId($storeId)->load($category->getId());
			$count = $cat->getProductCount();
			$array .= '<li class="sub-level'.$level;
			if($level!=1) $array .= ' sub-levels';
			if($len==$i) $array .= ' last-element ';
			$i++;
			$array .=  '" >';
			if($category->hasChildren())
			{
				$array .= '<button class="open_level'.$level;
				$array .= ' sub-levels non-active"> + </button>';
			}
			else
				$array .= '<button class="sub-levels no-child"> + </button>';
			$array .= '<a href="#" onlcik="return false;">' .
			$cat->getName() . "(".$count.")</a>\n";
			$array .= "<div class='input-give'><input class='cats' value='".$this->getValueCat($category->getId(),$wholesaleid)."' type='text' name='category_ids[".$category->getId()."]' /> %</div>";
			if($category->hasChildren())
			{
				$children = Mage::getModel('catalog/category')->getCategories($category->getId());
				$array .=  $this->getCategoriesHtml($children,$level+1,$storeId,$wholesaleid);
			}
			$array .= '</li>';
		}
		return  $array . '</ul>';
	}

	//getting discount percentage for categories
	public function getValueCat($id, $wholesaleid=null)
	{
		if($wholesaleid!=null) {
			$model = Mage::getModel('wholesale/wholesale')->load($wholesaleid);
			$datas = $model->getData();
			$cat_ids = unserialize($datas['category_ids']);
			foreach($cat_ids as $k=>$v)
				if($k==$id) return $v;
		}
	}

	//get script for jQuery tree view
	public function addScript()
	{
		$html = "<script type='text/javascript'>
		//<![CDATA[
		jQuery(document).ready(function($){
			$('.sub-levels').click(function(event) {
				$(this).next().next().next('ul').stop(true,true).slideToggle(300);
				$(this).toggleClass('active');
				return false;
			});
			function show_all(get) {
				$(get).next().next().next('ul').stop(true,true).slideDown(300);
				$(get).removeClass('active');
				return false;
			}
			function hide_all(get) {
				$(get).next().next().next('ul').stop(true,true).slideUp(300);
				$(get).addClass('active');
				return false;
			}
			$('#collapse').click(function() {
				$('.sub-levels').each(function() {
					hide_all(this);
				});
			});
			$('#expand').click(function() {
				$('.sub-levels').each(function() {
					show_all(this);
				});
			});
		});
		//]]>
        </script>";
        return $html;
	}

	//get expand and collapse button
	public function getExpandCollapse()
	{
		$html = "<a href='javascript:void(0)' id='expand' >Expand</a> | ";
		$html .= "<a href='javascript:void(0)' id='collapse' >Collapse</a>";
		return $html;
	}

}
