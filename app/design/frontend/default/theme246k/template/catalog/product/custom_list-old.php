
<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    design
 * @package     base_default
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
?>

<?php
/**
 * Product list template
 *
 * @see Mage_Catalog_Block_Product_List
 */
?>
<?php
    $_productCollection=$this->getLoadedProductCollection();
    $_helper = $this->helper('catalog/output');
?>
<?php if(!$_productCollection->count()): ?>


<p class="note-msg"><?php echo $this->__('There are no products matching the selection.') ?></p>
<?php else: ?>
<div class="category-products">  
    
    <?php $_collectionSize = $_productCollection->count() ?>
    <?php $_columnCount = $this->getColumnCount(); ?>    
        <div id="category">
	    <div class="viewport">
        <ul class="custom-list">
       
    	<?php $i=0; foreach ($_productCollection as $_product): ?>  
       
            <li class="custom-list"> <div class="layout"> <div>
            
              <div class="custom-div1">
            	 <h2 class="product-sku" >
            	<a href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $this->stripTags($_product->getName(), null, true) ?>"><?php echo $_helper->productAttribute($_product, $_product->getSKU(), 'SKU') ?></a></div>
            
            <!-- <div class="custom-div2">
            	<h2 class="product-name" ><a href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $this->stripTags($_product->getName(), null, true) ?>"><?php echo $_helper->productAttribute($_product, $_product->getName(), 'name') ?></a></h2></div> -->
            
            <div class="custom-div3"><a href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $this->stripTags($_product->getName(), null, true) ?>"><?php echo $_helper->productAttribute($_product, $_product->getAlt_description(), 'alt-description') ?></a></div>
            
            <div class="custom-div4">
                
                <?php if($_product->isSaleable()): ?>
                <?php echo $this->getPriceHtml($_product, true) ?></div>
                
            <div class="custom-div5">
                <button type="button" title="<?php echo $this->__('Add to Cart') ?>" onclick="setLocation('<?php echo $this->getAddToCartUrl($_product) ?>')"><span><span><?php echo $this->__('Add to Cart') ?></span></span></button> </div>
                
            <div class="custom-div6">
                                   <ul class="add-to-links">                                            
                        <?php if($_compareUrl=$this->getAddToCompareUrl($_product)): ?>
                        	<li style="margin: 0;padding: 0;width: 35px;"><a href="<?php echo $_compareUrl ?>" class="link-compare"><img title="<?php echo $this->__('Add to Compare') ?>" src="<?php  echo $this->getSkinUrl('images/compare.png');?>" /></a></li>                            
                        <?php endif; ?>
                        <?php if ($this->helper('wishlist')->isAllow()) : ?>
                        	<li style="height: 20px;margin: 0;padding: 0;width: 35px;"><span style="margin: 5;" class="separator">|</span> <a href="<?php echo $this->helper('wishlist')->getAddUrl($_product) ?>" class="link-wishlist"><img title="<?php echo $this->__('Add to Wishlist') ?>" src="<?php  echo $this->getSkinUrl('images/green_heart.png');?>" /></a></li>                  
                        <?php endif; ?>
                  </ul>   </div> 
                  
                  
                    <?php else: ?>
                    <?php endif; ?>
            </li><div align="center"><hr /></div>
        <?php endforeach ?>
        </ul>
        </div>

</div>
       
        <script type="text/javascript">decorateGeneric($$('ul.products-grid'), ['odd','even','first','last'])</script>
</div>
<?php endif; ?>
