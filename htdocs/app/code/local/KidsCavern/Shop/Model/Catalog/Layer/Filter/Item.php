<?php

class KidsCavern_Shop_Model_Catalog_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item {
    
    public function getUrl($current = true)
    {
        $query = array(
            $this->getFilter()->getRequestVar()=>$this->getValue(),
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null // exclude current page from urls
        );
        Mage::log($query);
        return Mage::getUrl('*/*/*', array('_current'=>$current, '_use_rewrite'=>true, '_query'=>$query));
    }
    
}
