<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Model_Cloud_Local extends Mageplace_Backup_Model_Cloud
{
	const FILE_PART_MAX_SIZE	= 0;
	
	protected function _construct()
	{
		parent::_construct();
	}
	
	public function needAuthorize()
	{
		return false;
	}
}
