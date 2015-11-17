<?php

class MagicToolbox_MagicSlideshow_Model_Observer {

    public function __construct() {

    }


    /*public function controller_action_predispatch($observer) {

    }*/

    /*public function beforeLoadLayout($observer) {

    }*/

    public function fixLayoutUpdates($observer) {
        //NOTE: to prevent an override of our templates with other modules

        //NOTE: replace node to prevent dublicate
        //NOTE: SimpleXMLElement creates a node instead of empty values, so we use fake file name
        $child = new Varien_Simplexml_Element('<magicslideshow module="MagicToolbox_MagicSlideshow"><file>magictoolbox.xml</file></magicslideshow>');
        Mage::app()->getConfig()->getNode('frontend/layout/updates')->extendChild($child, true);

        //NOTE: add new node to the end
        $child = new Varien_Simplexml_Element('<magicslideshow_layout_update module="MagicToolbox_MagicSlideshow"><file>magicslideshow.xml</file></magicslideshow_layout_update>');
        Mage::app()->getConfig()->getNode('frontend/layout/updates')->appendChild($child);


    }

    /*public function controller_action_postdispatch($observer) {

    }*/


}

?>