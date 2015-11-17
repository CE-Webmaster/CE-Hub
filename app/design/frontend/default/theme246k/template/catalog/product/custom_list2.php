
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
     <div class="custom-div1"><span class="listlabel">Product Code</span></div>
     <div class="custom-div2"><span class="listlabel">Description</span></div>
     <div class="custom-div3"><span class="listlabel">List Price</span></div>
     <div class="custom-div4"><span class="listlabel">Our Price</span></div>
     <div class="custom-div5"></div> 
    <?php $_collectionSize = $_productCollection->count() ?>
    <?php $_columnCount = $this->getColumnCount(); ?>    
 <div id="category">

       <ul class="custom-list">
             	<?php $i=0; foreach ($_productCollection as $_product): ?>  
       <li class="custom-list"> <div class="layout"> <div>
       <div class="custom-div1">
            	 <h2 class="product-sku" >
            	<a href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $this->stripTags($_product->getName(), null, true) ?>"><?php echo $_helper->productAttribute($_product, $_product->getSKU(), 'SKU') ?></a></div>
      
       <div class="custom-div2"><a href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $this->stripTags($_product->getName(), null, true) ?>"><?php echo $_helper->productAttribute($_product, $_product->getAlt_description(), 'alt-description') ?></a></div>
            
       <div class="custom-div3">
                <?php if($_product->isSaleable()): ?>
                <span class="msrp-price"><div class="price">$<?php echo Mage::helper('core')->currency($_product->getMsrp(),false,false); ?> 
     	</div>
        </span>
      </div>
      <div class="custom-div4">
                <span class="old-price"><div class="price">$<?php echo Mage::helper('core')->currency($_product->getPrice(),false,false); ?></div></span>          
            </div>
      <div class="custom-div5">    
		<button type="button" title="<?php echo $this->__('Add to Cart') ?>" onclick="setLocation('<?php echo $this->getAddToCartUrl($_product) ?>')"><span><span><?php echo $this->__('Add to Cart') ?></span></span></button> </div>
		<?php else: ?>
          <?php endif; ?>
            </li><div align="center"><hr /></div>
        <?php endforeach ?>
        </ul>


</div>
       
   
</div>
<?php endif; ?>
