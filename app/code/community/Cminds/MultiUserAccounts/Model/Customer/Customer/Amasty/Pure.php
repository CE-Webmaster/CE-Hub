<?php /* added automatically by conflict fixing tool */ if (Mage::getConfig()->getNode('modules/Amasty_Customerattr/active')) {
                class Cminds_MultiUserAccounts_Model_Customer_Customer_Amasty_Pure extends Amasty_Customerattr_Model_Rewrite_Customer {}
            } else { class Cminds_MultiUserAccounts_Model_Customer_Customer_Amasty_Pure extends Mage_Customer_Model_Customer {} } ?>