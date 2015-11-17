<?php

/**
 * Aumento.io
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package     Aumento_PriceHide
 * @copyright   Copyright (c) 2012 Aumentio.io (http://www.aumento.io)
 * @license     http://www.gnu.org/licenses  GNU General Public License, version 3 (GPL-3.0)
 */


class Aumento_PriceHide_Block_Price extends Mage_Catalog_Block_Product_Price {

	public $config_message = '';

	private $config_enabled = '';

	protected function _construct() {
		parent::_construct();
		
		$this->config_message = Mage::getStoreConfig('catalog/pricehideconfig/title'); 
		$this->config_enabled = Mage::getStoreConfig('catalog/pricehideconfig/active');
	}



	protected function _toHtml(){

		if (  ($this->config_enabled == 1) && !($this->helper('customer')->isLoggedIn()) ) {
			$this->setTemplate('pricehide/price.phtml');
		}

		if (!$this->getProduct() || $this->getProduct()->getCanShowPrice() === false) {
			return '';
		}

		return parent::_toHtml();
	}

}