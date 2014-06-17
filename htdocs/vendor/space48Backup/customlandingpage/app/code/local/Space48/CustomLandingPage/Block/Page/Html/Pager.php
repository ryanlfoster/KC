<?php
/**
 * Space48 Ltd
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.space48.com/license.html
 *
 * @category   Space48
 * @package    Space48_Custom_Landing_Page
 * @version    0.1.0
 * @copyright  Copyright (c) 2013-2013 Space48 Ltd. (http://www.space48.com)
 * @license    http://www.space48.com/license.html
 * @company    Space48
 * @author     James Cowie (james@space48.com), Matt Edwards (matt@space48.com)
 * @link       http://wiki.space48.com/modules/brands
 */
class Space48_CustomLandingPage_Block_Page_Html_Pager extends Mage_Page_Block_Html_Pager
{
    /*
     * Rewrites the url when changing page so that it stays as brand name/params
     * 
     * @return mixed
     */
    public function getPagerUrl($params=array())
    {
        $brand     = Mage::registry('brand');
        $brandName = $brand[0]['url_key'];
        
        $urlParams = array();
        $urlParams['_current'] = true;
        $urlParams['_escape']  = true;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query'] = $params;
        
        return $this->getUrl($brandName, $urlParams);
    }
}