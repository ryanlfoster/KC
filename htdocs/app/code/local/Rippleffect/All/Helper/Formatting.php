<?php

class Rippleffect_All_Helper_Formatting extends Mage_Core_Helper_Abstract {
    
    public function wrapInPTags($text) {
        
        $text = str_replace(array("<p>", "</p>"), array("", "<br />"), $text);
        $text = trim(str_replace(array("<br />", "<br>", "\r"), "\n", $text));
        
        $lines = explode("\n", $text);
        
        $finalText = "";
        foreach ($lines as $line) {
            if ($line == "") {
                continue;
            }
            
            $finalText .= "<p>" . $line . "</p>\n";
        }
        
        return $finalText;
    }
    
}
