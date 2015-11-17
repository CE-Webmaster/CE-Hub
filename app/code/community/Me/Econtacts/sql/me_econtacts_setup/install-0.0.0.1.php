<?php
/**
 * Me_Econtacts setup me_econtacts table
 *
 * @category  Me
 * @package   Me_Econtacts
 * @author    Attila Sági <sagi.attila@magevolve.com>
 * @copyright 2015 Magevolve Ltd. (http://magevolve.com)
 * @license   http://magevolve.com/terms-and-conditions Magevolve Ltd. License
 * @link      http://magevolve.com
 */

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 * Creating table me_econtacts
 */

if (!$installer->getConnection()->isTableExists($installer->getTable('me_econtacts/econtacts'))) {

    $table = $installer->getConnection()
        ->newTable($installer->getTable('me_econtacts/econtacts'))
        ->addColumn(
            'econtacts_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned' => true,
                'identity' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'Entity id'
        )
        ->addColumn(
            'name', Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(
                'nullable' => true,
            ),
            'Name'
        )
        ->addColumn(
            'email',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(
                'nullable' => true,
            ),
            'Email'
        )
        ->addColumn(
            'telephone',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            255,
            array(
                'nullable' => true,
            ),
            'Telephone'
        )
        ->addColumn(
            'comment',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '2M',
            array(
                'nullable' => true,
                'default' => null,
            ),
            'Comment'
        )
        ->addColumn(
            'answer',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '2M',
            array(
                'nullable' => true,
                'default' => null,
            ),
            'Answer'
        )
        ->addColumn(
            'answered',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'nullable' => false,
                'default' => 0,
            ),
            'Answered'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            null,
            array(
                'nullable' => true,
                'default' => null,
            ),
            'Creation Time'
        )
        ->addColumn(
            'store_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned' => true,
                'nullable' => false,
            ),
            'Store Id'
        )
        ->addIndex(
            $installer->getIdxName(
                'me_econtacts/econtacts',
                array('store_id')
            ),
            array('store_id')
        )
        ->addForeignKey(
            $installer->getFkName(
                'me_econtacts/econtacts',
                'store_id',
                'core/store',
                'store_id'
            ),
            'store_id',
            $installer->getTable('core/store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Econtacts table');

    $installer->getConnection()->createTable($table);

}
