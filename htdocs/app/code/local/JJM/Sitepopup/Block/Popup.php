<?php

/**
 * Class JJM_Sitepopup_Block_Popup
 *
 * @category JJM
 * @package Sitepopup
 * @author Jamie Murphy  <jay.murphy15@gmail.com>
 * @copyright Copyright (c) 2014 Jamie Murphy
 */
class JJM_Sitepopup_Block_Popup extends Mage_Core_Block_Template
{
    /*
     * Checks if module is enabled in admin
     * @return int
     */
    public function isEnabled() {
        return Mage::getStoreConfig('JJM_Sitepopup/settings/popupenabled');
    }

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

    /*
     * Called from front end. Checks if popup is to be displayed
     * if not disabled
     * if no cookie return true & create cookie
     * if cookie is dates return true & update cookie
     * @return Bool
     */
    public function isDisplayed() {

        if($this->isEnabled() == 0) {
            if($this->hasCookie()) {
                $this->deleteCookie();
            }

            return false;
        }

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
        return urlencode($this->getPopupUrl()).$this->getPopupImage();
    }

    /*
     * Update cookie values. Deletes old cookie & creates new one
     */
    public function updateCookie() {
        $this->deleteCookie();
        $this->createPopupCookie();
    }

    public function deleteCookie() {
        Mage::getModel('core/cookie')->delete('popupData');
    }

}