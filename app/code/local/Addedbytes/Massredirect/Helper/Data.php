<?php
class Addedbytes_Massredirect_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Function takes a URL as input and normalises it, at least a little.
     * It removed leading and trailing slashes, if present, as well as
     * recognized file extensions.
     */
    public static function cleanUrl($url)
    {
        $url = trim($url);
        $extension = strtolower(pathinfo($url, PATHINFO_EXTENSION));
        if (in_array($extension, array('php', 'asp', 'aspx', 'htm', 'html'))) {
            $url = preg_replace('/\.' . $extension . '$/i', '', $url);
        }
        $url = trim($url, '/');

        return $url;
    }

    /**
     * Pass in a line from a CSV file, and this method will return an
     * array of the line contents. Function adapted from PHP manual.
     * @param  string $csvLine   Raw line from CSV to process
     * @param  string $delimiter Delimiter, comma by default
     * @param  string $enclosure Enclosure character, quote by default
     * @param  string $escape    Escape character, backslash by default
     * @return array  Array of values from CSV line
     */
    public static function getCsvLine($csvLine, $delimiter = ",", $enclosure = '"', $escape = '\\')
    {
        $return = false;
        if (function_exists('str_getcsv')) {
            $return = str_getcsv($csvLine, $delimiter, $enclosure, $escape);
        } else {
            // Use PHP to process CSV
            $fhr = fopen('php://temp', 'r+');
            fwrite($fhr, $csvLine);
            rewind($fhr);
            $return = fgetcsv($fhr);
            fclose($fhr);
        }

        return $return;
    }
}
