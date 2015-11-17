<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Mageplace_Hideprice
 */
class Mageplace_Hideprice_Model_Mysql4_Hideprice extends Mage_Core_Model_Mysql4_Abstract
{
	const FIELD_CATEGORY_ID      = 'id_cat';
	const FIELD_PRODUCT_ID       = 'id_prod';
	const FIELD_IS_HIDEPRICE     = 'hideprice';
	const FIELD_HP_ID            = 'hp_id';
	const FIELD_STORE_ID         = 'store_id';
	const FIELD_IS_EXCL_CHILDREN = 'excl_children';
	const FIELD_HIDEPRICE_MODE   = 'hp_mode';
	const FIELD_GROUPS           = 'groups';
	const FIELD_PRIORITY         = 'priority';

	protected $_storeTable;

	protected function _construct()
	{
		$this->_init("mageplace_hideprice/hideprice", "id");

		$this->_storeTable = $this->getTable('mageplace_hideprice/hideprice_store');
	}

	public function maxPriority($storeId)
	{
		$select = $this->_getReadAdapter()->select()
			->from($this->getMainTable(), new Zend_Db_Expr('MAX(priority)'))
			->join(
				array('store_table' => $this->_storeTable),
				$this->getMainTable() . '.' . $this->getIdFieldName() . ' = store_table.' . self::FIELD_HP_ID,
				array())
			->where('id_cat IS NOT NULL');
		//->orWhere("groups LIKE '$groupId' OR groups LIKE '%,$groupId' OR groups LIKE '$groupId,%' OR groups LIKE '%,$groupId,%'");

		if (is_array($storeId)) {
			$select->where('store_table.' . self::FIELD_STORE_ID . ' IN (?)', array_map('intval', $storeId));
		} else {
			$select->where('store_table.' . self::FIELD_STORE_ID . ' = ?', (int)$storeId);
		}

		return $this->_getReadAdapter()->fetchOne($select);
	}

	public function getCategoriesPriorities($storeId)
	{
		$maxPriority = (int)$this->maxPriority($storeId);

		$select = $this->_getReadAdapter()->select()
			->from($this->getMainTable(), 'id_cat')
			->join(
				array('store_table' => $this->_storeTable),
				$this->getMainTable() . '.' . $this->getIdFieldName() . ' = store_table.' . self::FIELD_HP_ID,
				array())
			->where($this->getMainTable() . '.id_cat IS NOT NULL')
			->where('store_table.priority = ?', $maxPriority)
			->order('store_table.' . self::FIELD_STORE_ID . ' DESC')
			->order($this->getMainTable() . '.id_cat ASC');

		if (is_array($storeId)) {
			$select->where('store_table.' . self::FIELD_STORE_ID . ' IN (?)', array_map('intval', $storeId));
		} else {
			$select->where('store_table.' . self::FIELD_STORE_ID . ' = ?', (int)$storeId);
		}

		return $this->_getReadAdapter()->fetchCol($select);
	}

	public function loadByStoreId($id, $field, $storeId)
	{
		$readConn = $this->_getReadAdapter();

		$select = $readConn->select()
			->from($this->getMainTable(), $this->getIdFieldName())
			->join(
				array('store_table' => $this->_storeTable),
				$this->getMainTable() . '.' . $this->getIdFieldName() . ' = store_table.' . self::FIELD_HP_ID,
				array('*'))
			->where($this->getMainTable() . '.' . $field . ' = ?', $id);

		if (is_array($storeId)) {
			$select->where('store_table.' . self::FIELD_STORE_ID . ' IN (?)', array_map('intval', $storeId))
				->order('store_table.' . self::FIELD_STORE_ID . ' DESC');
		} else {
			$select->where('store_table.' . self::FIELD_STORE_ID . ' = ?', (int)$storeId);
		}

		return $readConn->fetchRow($select);
	}

	protected function _afterSave(Mage_Core_Model_Abstract $object)
	{
		parent::_afterSave($object);

		// process object to store relation
		$stores = $object->getData('store_id');
		if (is_array($stores)) {
			if (empty($stores) || in_array('0', $stores, true)) {
				$stores = array('0');
			}
		} else {
			$stores = array_map('intval', explode(',', strval($stores)));
		}

		$mode     = $object->getData('mode');
		$exclCh   = $object->getData('exclude_children');
		$priority = $object->getData(self::FIELD_PRIORITY);
		$groups   = $object->getData(self::FIELD_GROUPS);
		if (is_array($groups)) {
			$groups = implode(',', $groups);
		} else {
			$groups = (string)$groups;
		}
		foreach ($stores as $store) {
			$this->_getWriteAdapter()->delete(
				$this->_storeTable,
				$this->_getWriteAdapter()->quoteInto('hp_id = ?', $object->getId()) . ' AND ' . $this->_getWriteAdapter()->quoteInto('store_id = ?', $store)
			);

			if (!$object->getData('delete')) {
				$this->_getWriteAdapter()->insert(
					$this->_storeTable,
					array(
						self::FIELD_HP_ID            => $object->getId(),
						self::FIELD_STORE_ID         => $store,
						self::FIELD_HIDEPRICE_MODE   => $mode,
						self::FIELD_IS_EXCL_CHILDREN => (int)(bool)$exclCh,
						self::FIELD_GROUPS           => $groups,
						self::FIELD_PRIORITY         => (int)$priority,
					)
				);
			}
		}

		return $this;
	}
}