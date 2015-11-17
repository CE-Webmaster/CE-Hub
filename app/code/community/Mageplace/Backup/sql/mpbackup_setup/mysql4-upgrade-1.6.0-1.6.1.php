<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

$source = Mage::getBaseDir('tmp') . DS . 'lib' . DS . 'Zend';
$dest = Mage::getBaseDir('lib') . DS . 'Zend';


try {
	$classExists = class_exists('Zend_Oauth');
} catch(Exception $e) {
	$classExists = false;
}

if(!$classExists && (!file_exists($dest . DS . 'Oauth.php') || !file_exists($dest . DS . 'Oauth')) && file_exists($source)) {
	Mage::helper('mpbackup')->copyDirectory($source, $dest);
}

if(file_exists($source)) {
	Mage::helper('mpbackup')->deleteDirectory($source);
}