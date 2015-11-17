<?php
class Addedbytes_Massredirect_Model_Mysql4_Import extends Mage_Core_Model_Mysql4_Abstract
{

    protected function _construct()
    {
        $this->_init('addedbytes_massredirect/massredirect', 'redirect_id');
    }

    /**
     * Function to process an uploaded CSV. Tested with several thousand rows and seems fine.
     * Does some basic validation on the file before running it, and reports errors and
     * success count to the user once finished.
     */
    public function uploadAndImport(Varien_Object $object)
    {

        // Load session and any other useful variables
        $session = Mage::getSingleton('adminhtml/session');
        $table = Mage::getSingleton('core/resource')->getTableName('massredirect/massredirect');
        $websiteId = $object->getScopeId();

        // CSV Information
        $importFile = $_FILES["groups"]["tmp_name"]["import_data"]["fields"]["massredirect_import"]["value"];

        if (!empty($importFile)) {

            // Store errors for processing in a batch later
            $errors = array();

            // Load CSV
            try {
                $csv = trim(file_get_contents($importFile));
            } catch (Exception $e) {
                $errors[] = Mage::helper('adminhtml')->__('Error opening CSV file.');
                $errors[] = $e;
            }

            if (!empty($csv)) {

                $csvLines = explode("\n", $csv);
                foreach ($csvLines as $k => $csvLine) {
                    $csvLine = Mage::helper('addedbytesmassredirect')->getCsvLine($csvLine);
                    if (count($csvLine) != 3) {
                        $errors[] = Mage::helper('adminhtml')->__('Invalid File Format at line %s', $csvLine);
                    }
                }

                // If errors above, we can't continue
                if (!empty($errors)) {
                    throw new Exception("\n" . implode("\n", $errors));
                }

                // No errors, so let's process the data
                if (empty($errors)) {

                    // Set up connection
                    $write = $this->_getWriteAdapter();

                    // Delete existing data for this website
                    $condition = array(
                        $write->quoteInto('website_id = ?', $websiteId),
                    );
                    $write->delete($table, $condition);

                    // Loop through file
                    $data = array();
                    $rowsInserted = 0;
                    $first = true;
                    foreach ($csvLines as $k => $csvLine) {

                        // Skip first line - it contains the headings
                        if ($first) {
                            $first = false;
                            continue;
                        }

                        // Function requires PHP 5.3.0 and up - maybe replace?
                        $csvLine = Mage::helper('addedbytesmassredirect')->getCsvLine($csvLine);

                        // Old URL cannot be blank
                        if (trim($csvLine[0]) == '') {
                            $errors[] = Mage::helper('adminhtml')->__('Row %s does not contain an old URL.', ($k+1));
                        }

                        // Verify that either the New URL or SKU column are set
                        if ((trim($csvLine[1]) == '') && (trim($csvLine[2]) == '')) {
                            $errors[] = Mage::helper('adminhtml')->__('Row %s must contain either a SKU or a New URL.', ($k+1));
                        }

                        // Clean Old URL as much as possible
                        $oldUrl = Mage::helper('addedbytesmassredirect')->cleanUrl($csvLine[0]);

                        // If no errors, add to data array to write
                        if (empty($errors)) {
                            $data[] = array(
                                'website_id'    => $websiteId,
                                'old_url'       => $oldUrl,
                                'sku'           => $csvLine[1],
                                'new_url'       => $csvLine[2],
                            );
                        }

                    }

                    // Write whatever data has made it through validation
                    foreach ($data as $k => $dataLine) {
                        try {
                            $write->insert($table, $dataLine);
                            $rowsInserted++;
                        } catch (Exception $e) {
                            $errors[] = Mage::helper('adminhtml')->__('Error in Row #%s', ($k+1));
                            $errors[] = $e;
                        }
                    }

                    $session->addSuccess(Mage::helper('adminhtml')->__($rowsInserted . ' rows (of ' . count($csvLines) . ') have been imported.'));
                }
            }
        }

        if (!empty($errors)) {
            throw new Exception("\n" . implode("\n", $errors));
        }

    }
}
