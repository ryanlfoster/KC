<?php

/**
 * Class JJM_Sitepopup_Block_Popup
 *
 * @category Cti
 * @package Trustpilot
 * @author Jamie Murphy - CTI Digital <j.murphy@ctidigital.com>
 * @copyright Copyright (c) 2014 CTI Digital (http://www.ctidigital.com)
 */
class JJM_Sitepopup_Block_Popup extends Mage_Core_Block_Template
{

    /*
     * get popup url from admin
     * @return $url
     */
    public function getPopupUrl() {
        return Mage::getStoreConfig('JJM_Sitepopup/settings/popupurl');
    }

    /*
     * get popup image from admin
     * @return $image
     */
    public function getPopupImage() {
        return Mage::getStoreConfig('JJM_Sitepopup/settings/popupimage');
    }

    public function isDisplayed() {
        if(!$this->hasCookie()) {
            $this->createPopupCookie();
            return true;
        }


        $cookie = $this->getCookie();
        if(!$this->isCookieCurrent($cookie)) {
            $this->updateCookie();
            return true;
        }

        return false;
    }

    /*
     * Checks if user has cookie set
     * @return Bool
     */
    public function hasCookie() {
        $cookie = $this->getCookie();

        if($cookie) {
            return true;
        }

        return false;
    }

    /*
     * checks if cookie value is outdated
     * @return Bool
     */
    public function isCookieCurrent($cookie) {
        $expectedValue = $this->createCookieData();
        if($expectedValue === $cookie) {
            return true;
        }

        return false;
    }

    /*
     * Get the cookie if exists. If not create and return
     */
    public function getCookie() {
        return Mage::getModel('core/cookie')->get('popupData');
    }

    /*
     * creates cookie value using data as unique string
     * @returns $cookie
     */
    public function createPopupCookie() {
        $data = $this->createCookieData();
        $cookie = Mage::getModel('core/cookie')->set('popupData', $data, 31536e3);

        return $cookie;
    }

    /*
     * creates standard cookie data value
     * @return STRING $data;
     */
    public function createCookieData() {
        return $this->getPopupUrl().$this->getPopupImage();
    }

    /*
     * Update cookie values. Deletes old cookie & creates new one
     */
    public function updateCookie() {
        Mage::getModel('core/cookie')->delete('popupData');
        $this->createPopupCookie();
    }

}