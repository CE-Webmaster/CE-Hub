<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Block_Adminhtml_Profile_Edit_Tables extends Mage_Core_Block_Template
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->setTemplate('mpbackup/tables.phtml');
	}

	public function getDBTables()
	{
		static $tables = null;
		
		if(is_null($tables)) {
			$DBTables = Mage::getModel('mpbackup/db')->getTables();
			$excluded = $this->getExcludedTables();
			foreach($DBTables as $table) {
				$tables[] = array(
					'name'		=> $table,
					'checked'	=> in_array($table, $excluded),
				);
			}
		}
		
		return $tables;
	}
	
	public function getProfile()
	{
		return Mage::registry('mpbackup_profile');
	}
	
	public function getExcludedTables()
	{
		return $this->getProfile()->getExcludedTables();
	}
}