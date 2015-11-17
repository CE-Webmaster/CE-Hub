<?php

class MagicToolbox_MagicScroll_Helper_Params extends Mage_Core_Helper_Abstract {

    public function __construct() {


    }

    public function getBlocks() {
		return array(
			'default' => 'Defaults',
			'customslideshowblock' => 'Homepage or custom block',
			'product' => 'Product page',
			'recentlyviewedproductsblock' => 'Recently Viewed Products block'
		);
	}

	public function getDefaultValues() {
		return array(
			'customslideshowblock' => array(
				'enable-effect' => 'No',
				'direction' => 'left',
			),
			'product' => array(
				'enable-effect' => 'Yes',
				'step' => '1',
				'items' => '1',
				'arrows' => 'inside',
			),
			'recentlyviewedproductsblock' => array(
				'enable-effect' => 'No',
				'thumb-max-width' => '135',
				'thumb-max-height' => '135',
			)
		);
	}

	public function getParamsMap($block) {
		$blocks = array(
			'default' => array(
				'General' => array(
					'include-headers-on-all-pages'
				),
				'Positioning and Geometry' => array(
					'thumb-max-width',
					'thumb-max-height',
					'square-images'
				),
				'Miscellaneous' => array(
					'item-tag',
					'link-to-product-page'
				),
				'Scroll' => array(
					'scroll-style',
					'show-image-title',
					'loop',
					'speed',
					'width',
					'height',
					'item-width',
					'item-height',
					'step',
					'items'
				),
				'Scroll Arrows' => array(
					'arrows',
					'arrows-opacity',
					'arrows-hover-opacity'
				),
				'Scroll Slider' => array(
					'slider-size',
					'slider'
				),
				'Scroll effect' => array(
					'direction',
					'duration'
				)
			),
			'customslideshowblock' => array(
				'General' => array(
					'enable-effect',
					'block-title'
				),
				'Positioning and Geometry' => array(
					'thumb-max-width',
					'thumb-max-height',
					'square-images'
				),
				'Scroll' => array(
					'scroll-style',
					'show-image-title',
					'loop',
					'speed',
					'width',
					'height',
					'item-width',
					'item-height',
					'step',
					'items'
				),
				'Scroll Arrows' => array(
					'arrows',
					'arrows-opacity',
					'arrows-hover-opacity'
				),
				'Scroll Slider' => array(
					'slider-size',
					'slider'
				),
				'Scroll effect' => array(
					'direction',
					'duration'
				)
			),
			'product' => array(
				'General' => array(
					'enable-effect'
				),
				'Positioning and Geometry' => array(
					'thumb-max-width',
					'thumb-max-height',
					'square-images'
				),
				'Multiple images' => array(
					'use-individual-titles'
				),
				'Scroll' => array(
					'scroll-style',
					'show-image-title',
					'loop',
					'speed',
					'width',
					'height',
					'item-width',
					'item-height',
					'step',
					'items'
				),
				'Scroll Arrows' => array(
					'arrows',
					'arrows-opacity',
					'arrows-hover-opacity'
				),
				'Scroll Slider' => array(
					'slider-size',
					'slider'
				),
				'Scroll effect' => array(
					'direction',
					'duration'
				)
			),
			'recentlyviewedproductsblock' => array(
				'General' => array(
					'enable-effect'
				),
				'Positioning and Geometry' => array(
					'thumb-max-width',
					'thumb-max-height',
					'square-images'
				),
				'Miscellaneous' => array(
					'link-to-product-page'
				),
				'Scroll' => array(
					'scroll-style',
					'show-image-title',
					'loop',
					'speed',
					'width',
					'height',
					'item-width',
					'item-height',
					'step',
					'items'
				),
				'Scroll Arrows' => array(
					'arrows',
					'arrows-opacity',
					'arrows-hover-opacity'
				),
				'Scroll Slider' => array(
					'slider-size',
					'slider'
				),
				'Scroll effect' => array(
					'direction',
					'duration'
				)
			)
		);
		return $blocks[$block];
	}

}
