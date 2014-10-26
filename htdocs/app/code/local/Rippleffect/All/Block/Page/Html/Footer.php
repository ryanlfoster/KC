<?php

/**
 * Copyright footer filter
 *
 * @category   Rippleffect
 * @package    Rippleffect_All
 * @author     Dan Jones <djones@rippleffect.com>
 */
class Rippleffect_All_Block_Page_Html_Footer extends Mage_Page_Block_Html_Footer {

    /**
     * Get Copyright string from Admin. Replace &year& with current year to save having to
     * update it year on year.
     *
     * @return string
     */
    public function getCopyright() {
        $copyright = parent::getCopyright();
        $copyright = str_replace("&year&", date("Y"), $copyright);
        return $copyright;
    }

}