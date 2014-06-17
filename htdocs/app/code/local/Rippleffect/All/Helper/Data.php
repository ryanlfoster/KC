<?php

/**
 * Rippleffect general helper
 *
 * @category   Rippleffect
 * @package    Rippleffect_All
 * @author     Dan Jones <djones@rippleffect.com>
 */

class Rippleffect_All_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Attributes array cache
     *
     * @var array
     */
    private $_attrs = array();

    /**
     * List of IP addresses/schemes considered to be "internal" ranges,
     * to exclude from response of getRemoteAddr();
     *
     * @var array
     */
    protected $_internalIpSchemes = array(
        "127\.0\.0\.1",
        "^10\.",
        "172\.16\.",
        "192\.168\.",
    );

    /**
     * Get the numerical value of an attribute textual value from a multi-select attribute
     *
     * @param string $attribute
     * @param string $value
     * @return string
     */
    public function getAttributeValueId($attribute, $value) {
        if (!isset($this->_attrs[$attribute]) || (isset($this->_attrs[$attribute]) && !is_array($this->_attrs[$attribute]))) {
            $this->_attrs[$attribute] = array();
        }
        
        if (count($this->_attrs[$attribute]) == 0) {
            $attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
                                    ->setCodeFilter($attribute)
                                    ->getFirstItem();
            $attributeOptions = $attributeInfo->getSource()->getAllOptions(false);
            foreach ($attributeOptions as $option) {
                $this->_attrs[$attribute][$option['label']] = $option['value'];
            }
        }
        if (isset($this->_attrs[$attribute][$value])) {
            return $this->_attrs[$attribute][$value];
        }
        else {
            return false;
        }
    }

    /**
     * Get the actual IP of the remote user when Magento is behind a
     * reverse proxy. Useful for Rippleffect Amazon AWS hosting environment.
     *
     * @return string
     */
    public function getRemoteAddr() {
        $remote_addr = $_SERVER['REMOTE_ADDR'];
        $pieces = explode(",", $remote_addr);
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $pieces = array_merge($pieces, explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']));
        }

        $remoteAddr = null;
        foreach ($pieces as $piece) {
            $internal = false;
            foreach ($this->_internalIpSchemes as $ip) {
                if (preg_match("/" . $ip . "/", $piece)) {
                    $internal = true;
                    break;
                }
            }
            if (!$internal) {
                $remoteAddr = $piece;
                break;
            }
        }

        if (!$remoteAddr) { // not been set. assume REMOTE_ADDR (internal environments?)
            return $_SERVER['REMOTE_ADDR'];
        }
        else {
            return $remoteAddr;
        }
    }
    
    // 2 is "Default Category" with Magento out of the box..
    public function getCategoryTree($rootCategoryId = 2) {
        $category = Mage::getModel( 'catalog/category' )->load(2);
        $this->_categoryRecursive($category, $cats);
        $this->_rCatSelect($cats, $op);
        return $op;
    }
   
    private function _rCatSelect($arr, &$op, $i = 0) {
        foreach ($arr as $k => $data) {
            if (is_numeric($k)) {
                $this->_rCatSelect($data, $op, $i + 1);
            }
            elseif ($k == "title" && $i > 1) {
                $op[] = array("id" => $arr['id'], "title" => $arr['title'], "level" => $i);
            }
        }
    }

    private function _categoryRecursive($category, &$cats, $i = 0) {
        $cats[$i]['title'] = $category->getName();
        $cats[$i]['id'] = $category->getId();

        $children = $category->getChildrenCategories();
        if ($children) {
            $x = 0;
            foreach ($children as $child) {
                $this->_categoryRecursive($child, $cats[$i], ++$x);
            }
        }
    }

    /**
      * @params mixed $msg Message to log.
      * @params boolean $dt Provide debug backtrace if true.
      */
    public function log($msg, $dt = null) {
        if (!is_string($msg) && !is_numeric($msg)) {
            $msg = print_r($msg, true);
        }

        if ((string)Mage::getConfig()->getNode('rippleffect/general/debug/include_file_path') == "1") {
            $dt = debug_backtrace();
            $msg = str_replace(Mage::getBaseDir() . "/", "", $dt[0]['file']) . ":" . $dt[1]['function'] . "(" . $dt[0]['line'] . ") :: " . $msg;
        }
        
        $this->_log($msg);
    }
    
    public function var_dump($msg, $dt = null) {
        $msg = var_export($msg, true);
        
        if ((string)Mage::getConfig()->getNode('rippleffect/general/debug/include_file_path') == "1") {
            $dt = debug_backtrace();
            $msg = str_replace(Mage::getBaseDir() . "/", "", $dt[0]['file']) . ":" . $dt[1]['function'] . "(" . $dt[0]['line'] . ") :: " . $msg;
        }
        
        $this->_log($msg);
    }
    
    protected function _log($msg) {
        if ((string)Mage::getConfig()->getNode('rippleffect/general/debug/enabled') != "1") {
            return true;
        }
        
        switch ((string)Mage::getConfig()->getNode('rippleffect/general/debug/logmethod')) {
            case "echo":
                echo date("Y-m-d H:i:s") . ": " . $msg . "\n";
                break;
            case "file":
                Mage::log($msg, null, (string)Mage::getConfig()->getNode('rippleffect/general/debug/logfile'));
                break;
        }
    }

}