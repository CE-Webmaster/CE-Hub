<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Backup
 */

class Mageplace_Backup_Block_Adminhtml_Backup extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	protected $_blockGroup = 'mpbackup';
	protected $_controller = 'adminhtml_backup';

	/**
	 * Constructor for Adminhtml Backup Block
	 */
	public function __construct()
	{
		$this->_createButtonLabel = $this->__('Create Backup');

		$this->_addCreateButton();

		parent::__construct();

		$this->removeButton('add');
	}

	protected function getCreateButtonLabel()
	{
		return $this->_createButtonLabel;
	}

	public function getCreateUrl()
	{
		return $this->getUrl('*/*/create');
	}

	protected function _addCreateButton()
	{
		$this->_addButton(
			'create',
			array(
				'label'		=> $this->getCreateButtonLabel(),
				'onclick'	=> 'setLocation(\'' . $this->getCreateUrl() .'\')',
				'class'		=> 'export',
			)
		);
	}

	public function getHeaderText()
	{
		return $this->__('Manage Backups');
	}

	public function getHeaderCssClass()
	{
			return 'icon-head head-backups-control';
	}
}