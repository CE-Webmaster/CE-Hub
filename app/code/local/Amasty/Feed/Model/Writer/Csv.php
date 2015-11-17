<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Feed
 */  
class Amasty_Feed_Model_Writer_Csv extends Amasty_Feed_Model_Writer_Abstract
{
    function write(){
        
        if ($this->isFirstStep()){
            
            $header = $this->_getFeed()->getCsvHeaderStatic();
            if (!empty($header)) {
                fwrite($this->fp, $header."\n");
            }
            
            if ($this->_getFeed()->getCsvHeader()) {
                $fields = $this->_getFeed()->getFields();
                $this->writeRecord($fields["name"]);
            }
            
            
            
        }
        
        return parent::write();
    }
    public function writeRecord($record)
    {
        $encl  = chr($this->_getFeed()->getCsvEnclosure());
        $delim = chr($this->_getFeed()->getCsvDelimiter());
        
        
        if ( $encl == 'n')
            $encl = chr(0);
        
        fputcsv($this->fp, $record, $delim, $encl);
    }     
}