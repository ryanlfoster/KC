<?php
class Space48_Brands_Block_Page_Html_Pager extends Mage_Page_Block_Html_Pager
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