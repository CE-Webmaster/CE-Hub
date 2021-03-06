<?php

if(!defined('MagicScrollModuleCoreClassLoaded')) {

    define('MagicScrollModuleCoreClassLoaded', true);

    require_once(dirname(__FILE__) . '/magictoolbox.params.class.php');

    /**
     * MagicScrollModuleCoreClass
     *
     */
    class MagicScrollModuleCoreClass {

        /**
         * MagicToolboxParamsClass class
         *
         * @var   MagicToolboxParamsClass
         *
         */
        var $params;

        /**
         * Tool type
         *
         * @var   string
         *
         */
        var $type = 'category';

        /**
         * Constructor
         *
         * @return void
         */
        function MagicScrollModuleCoreClass() {
            $this->params = new MagicToolboxParamsClass();
            $this->loadDefaults();
            $this->params->setMapping(array(
                'width' => array('0' => 'auto'),
                'height' => array('0' => 'auto'),
                'item-width' => array('0' => 'auto'),
                'item-height' => array('0' => 'auto'),
                //NOTE: what is it for?
            ));
        }

        /**
         * Metod to get headers string
         *
         * @param string $jsPath  Path to JS file
         * @param string $cssPath Path to CSS file
         *
         * @return string
         */
        function getHeadersTemplate($jsPath = '', $cssPath = null) {
            //to prevent multiple displaying of headers
            if(!defined('MagicScrollModuleHeaders')) {
                define('MagicScrollModuleHeaders', true);
            } else {
                return '';
            }
            if($cssPath == null) {
                $cssPath = $jsPath;
            }
            $headers = array();
            // add module version
            $headers[] = '<!-- Magic Scroll Magento module version v4.9.21 [v1.4.20-12-gec7b386:v1.0.29] -->';
            // add style link
            $headers[] = '<link type="text/css" href="' . $cssPath . '/magicscroll.css" rel="stylesheet" media="screen" />';
            // add script link
            $headers[] = '<script type="text/javascript" src="' . $jsPath . '/magicscroll.js"></script>';
            // add options
            $headers[] = $this->getOptionsTemplate();
            return "\r\n" . implode("\r\n", $headers) . "\r\n";
        }

        /**
         * Metod to get options string
         *
         * @param mixed $id Extra options ID
         *
         * @return string
         */
        function getOptionsTemplate($id = null) {
            $addition = '';
            if($this->params->paramExists('item-tag')) {
                $addition .= "\n\t\t'item-tag':'" . $this->params->getValue('item-tag') . "',";
            }
            //NOTICE: scope == 'MagicScroll'
            return "<script type=\"text/javascript\">\n\tMagicScroll.".($id == null?"options":"extraOptions.".$id)." = {{$addition}\n\t\t".$this->params->serialize(true, ",\n\t\t")."\n\t}\n</script>";
        }

        /**
         * Metod to get MagicScroll HTML
         *
         * @param array $itemsData MagicScroll data
         * @param array $params Additional params
         *
         * @return string
         */
        function getMainTemplate($itemsData, $params = array()) {

            $id = '';
            $width = '';
            $height = '';

            $html = array();

            extract($params);

            if(empty($width)) {
                $width = '';
            } else {
                $width = " width=\"{$width}\"";
            }
            if(empty($height)) {
                $height = '';
            } else {
                $height = " height=\"{$height}\"";
            }

            if(empty($id)) {
                $id = '';
            } else {
                // add personal options
                //NOTICE: what is it for?
                $html[] = $this->getOptionsTemplate($id);
                $id = ' id="' . addslashes($id) . '"';
            }

            // add div with tool className
            $additionalClasses = array(
                'default' => '',
                'with-borders' => 'msborder'
            );
            $additionalClass = $additionalClasses[$this->params->getValue('scroll-style')];
            if(!empty($additionalClass)) {
                $additionalClass = ' ' . $additionalClass;
            }
            $html[] = '<div' . $id . ' class="MagicScroll' . $additionalClass . '"' . $width . $height . '>';

            // add items
            foreach($itemsData as $item) {

                $img = '';
                $thumb = '';
                $link = '';
                $target = '';
                $alt = '';
                $title = '';
                $description = '';
                $width = '';
                $height = '';

                extract($item);

                // check big image
                if(empty($img)) {
                    $img = '';
                }

                //NOTE: remove this?
                if(isset($medium)) {
                    $thumb = $medium;
                }

                // check thumbnail
                if(!empty($img) || empty($thumb)) {
                    $thumb = $img;
                }

                // check item link
                if(empty($link)) {
                    $link = '';
                } else {
                    // check target
                    if(empty($target)) {
                        $target = '';
                    } else {
                        $target = ' target="' . $target . '"';
                    }
                    $link = $target . ' href="' . addslashes($link) . '"';
                }

                // check item alt tag
                if(empty($alt)) {
                    $alt = '';
                } else {
                    $alt = htmlspecialchars(htmlspecialchars_decode($alt, ENT_QUOTES));
                }

                // check title
                if(empty($title)) {
                    $title = '';
                } else {
                    $title = htmlspecialchars(htmlspecialchars_decode($title, ENT_QUOTES));
                    if(empty($alt)) {
                        $alt = $title;
                    }
                    if($this->params->checkValue('show-image-title', 'No')) {
                        $title = '';
                    }
                }

                // check description
                if(empty($description)) {
                    $description = '';
                } else {
                    //$description = preg_replace("/<(\/?)a([^>]*)>/is", "[$1a$2]", $description);
                    $description = "<span>{$description}</span>";
                }

                if(empty($width)) {
                    $width = '';
                } else {
                    $width = " width=\"{$width}\"";
                }
                if(empty($height)) {
                    $height = '';
                } else {
                    $height = " height=\"{$height}\"";
                }

                // add item
                $html[] = "<a{$link}><img{$width}{$height} src=\"{$thumb}\" alt=\"{$alt}\" />{$title}{$description}</a>";
            }

            // close core div
            $html[] = '</div>';

            // create HTML string
            $html = implode('', $html);

            // return result
            return $html;
        }

        /**
         * Metod to load defaults options
         *
         * @return void
         */
        function loadDefaults() {
            $params = array("enable-effect"=>array("id"=>"enable-effect","group"=>"General","order"=>"10","default"=>"Yes","label"=>"Enable Magic Scroll™","type"=>"array","subType"=>"select","values"=>array("Yes","No"),"scope"=>"profile"),"block-title"=>array("id"=>"block-title","group"=>"General","order"=>"20","default"=>"","label"=>"Block title","type"=>"text","scope"=>"profile"),"include-headers-on-all-pages"=>array("id"=>"include-headers-on-all-pages","group"=>"General","order"=>"21","default"=>"No","label"=>"Include headers on all pages","description"=>"To be able to apply an effect on any page","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"profile"),"thumb-max-width"=>array("id"=>"thumb-max-width","group"=>"Positioning and Geometry","order"=>"10","default"=>"250","label"=>"Maximum width of thumbnail (in pixels)","type"=>"num","scope"=>"profile"),"thumb-max-height"=>array("id"=>"thumb-max-height","group"=>"Positioning and Geometry","order"=>"11","default"=>"250","label"=>"Maximum height of thumbnail (in pixels)","type"=>"num","scope"=>"profile"),"square-images"=>array("id"=>"square-images","group"=>"Positioning and Geometry","order"=>"40","default"=>"No","label"=>"Always create square images","description"=>"","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"profile"),"use-individual-titles"=>array("id"=>"use-individual-titles","group"=>"Multiple images","order"=>"40","default"=>"Yes","label"=>"Use individual image titles for additional images?","type"=>"array","subType"=>"radio","values"=>array("Yes","No"),"scope"=>"profile"),"item-tag"=>array("id"=>"item-tag","group"=>"Miscellaneous","order"=>"0","default"=>"A","label"=>"Item tag","description"=>"HTML tag that will be recognized as item element","type"=>"array","subType"=>"radio","values"=>array("A","IMG","DIV"),"scope"=>"profile"),"link-to-product-page"=>array("id"=>"link-to-product-page","group"=>"Miscellaneous","order"=>"30","default"=>"Yes","label"=>"Link enlarged image to the product page","type"=>"array","subType"=>"select","values"=>array("Yes","No"),"scope"=>"profile"),"scroll-style"=>array("id"=>"scroll-style","group"=>"Scroll","order"=>"5","default"=>"default","label"=>"Style","type"=>"array","subType"=>"select","values"=>array("default","with-borders"),"scope"=>"profile"),"show-image-title"=>array("id"=>"show-image-title","group"=>"Scroll","order"=>"6","default"=>"Yes","label"=>"Show image title under images","type"=>"array","subType"=>"radio","values"=>array("Yes","No")),"loop"=>array("id"=>"loop","group"=>"Scroll","order"=>"10","default"=>"continue","label"=>"Restart scroll after last image","description"=>"After last item, continue or go back to beginning","type"=>"array","subType"=>"radio","values"=>array("continue","restart"),"scope"=>"tool"),"speed"=>array("id"=>"speed","group"=>"Scroll","order"=>"20","default"=>"5000","label"=>"Duration item displayed (milliseconds)","description"=>"0 = manual scroll; 5000 = 5 seconds","type"=>"num","scope"=>"tool"),"width"=>array("id"=>"width","group"=>"Scroll","order"=>"30","default"=>"0","label"=>"Scroll width (pixels)","description"=>"0 - auto","type"=>"num","scope"=>"tool"),"height"=>array("id"=>"height","group"=>"Scroll","order"=>"40","default"=>"0","label"=>"Scroll height (pixels)","description"=>"0 - auto","type"=>"num","scope"=>"tool"),"item-width"=>array("id"=>"item-width","group"=>"Scroll","order"=>"50","default"=>"0","label"=>"Width of entire scroll area (pixels)","description"=>"0 - auto","type"=>"num","scope"=>"tool"),"item-height"=>array("id"=>"item-height","group"=>"Scroll","order"=>"60","default"=>"0","label"=>"Height of entire scroll area (pixels)","description"=>"0 - auto","type"=>"num","scope"=>"tool"),"step"=>array("id"=>"step","group"=>"Scroll","order"=>"70","default"=>"3","label"=>"Number of items moved on each scroll","type"=>"num","scope"=>"tool"),"items"=>array("id"=>"items","group"=>"Scroll","order"=>"80","default"=>"3","label"=>"Items to show","description"=>"0 - manual","type"=>"num","scope"=>"tool"),"arrows"=>array("id"=>"arrows","group"=>"Scroll Arrows","order"=>"10","default"=>"outside","label"=>"Show arrows","label"=>"Where arrows should be placed","type"=>"array","subType"=>"radio","values"=>array("outside","inside","false"),"scope"=>"tool"),"arrows-opacity"=>array("id"=>"arrows-opacity","advanced"=>"1","group"=>"Scroll Arrows","order"=>"20","default"=>"60","label"=>"Arrows opacity (0-100)","description"=>"0 = transparent, 100 = solid color","type"=>"num","scope"=>"tool"),"arrows-hover-opacity"=>array("id"=>"arrows-hover-opacity","advanced"=>"1","group"=>"Scroll Arrows","order"=>"30","default"=>"100","label"=>"Arrows opacity on mouse over (0-100)","description"=>"0 = transparent, 100 = solid color","type"=>"num","scope"=>"tool"),"slider-size"=>array("id"=>"slider-size","group"=>"Scroll Slider","order"=>"10","default"=>"10%","label"=>"Slider size (numeric or percent)","type"=>"text","scope"=>"tool"),"slider"=>array("id"=>"slider","group"=>"Scroll Slider","order"=>"20","default"=>"false","label"=>"Slider postion","type"=>"array","subType"=>"select","values"=>array("top","right","bottom","left","false"),"scope"=>"tool"),"direction"=>array("id"=>"direction","group"=>"Scroll effect","order"=>"10","default"=>"right","value"=>"bottom","label"=>"Scroll direction","description"=>"bottom = scroll moves from bottom to top","type"=>"array","subType"=>"select","values"=>array("top","right","bottom","left"),"scope"=>"tool"),"duration"=>array("id"=>"duration","group"=>"Scroll effect","order"=>"20","default"=>"1000","label"=>"Duration of each scroll (milliseconds)","description"=>"e.g. 0 = instant; 1000 = 1 second","type"=>"num","scope"=>"tool"));
            $this->params->appendParams($params);
        }
    }

}

?>
