<?php
/**
* Copyright © Pulsestorm LLC: All rights reserved
*/
class Clevelandequipment_Commercebug_Model_Graphviz
{
    public function capture()
    {    
        $collector  = new Clevelandequipment_Commercebug_Model_Collectorgraphviz; 
        $o = new stdClass();
        $o->dot = Clevelandequipment_Commercebug_Model_Observer_Dot::renderGraph();
        $collector->collectInformation($o);
    }
    
    public function getShim()
    {
        $shim = Clevelandequipment_Commercebug_Model_Shim::getInstance();        
        return $shim;
    }    
}