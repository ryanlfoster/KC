<?php
class Space48_Brands_Block_Catalog_Product_List_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
    /*
     * Rewrites the url when changing the order so that it stays as brand name/params
     *
     * @return mixed
     */
    public function getPagerUrl($params=array())
    {
        $brand     = Mage::registry('brand');
        $brandName = $brand[0]['url_key'];

        $urlParams = array();
        $urlParams['_current']     = true;
        $urlParams['_escape']      = true;
        $urlParams['_use_rewrite'] = true;
        $urlParams['_query']       = $params;

        return $this->getUrl($brandName, $urlParams);
    }
}