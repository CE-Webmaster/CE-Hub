<?php
/*
 * Developer: Michael Jacky
* Team site: http://cmsideas.net/
* Support: http://support.cmsideas.net/
*
*/
class Glace_Productlabels_Model_Resource_Css extends Glace_Productlabels_Model_Resource_Abstract
{
	/**
     * Resource initialization
     */
    public function __construct()
    {
        $this->setType(Glace_Productlabels_Model_Css::ENTITY);
        $this->setConnection('productlabels_read', 'productlabels_write');
    }
	
}
?>