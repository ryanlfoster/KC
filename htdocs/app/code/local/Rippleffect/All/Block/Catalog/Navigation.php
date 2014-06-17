<?php

/**
 * Navigation customisations
 *
 * @category   Rippleffect
 * @package    Rippleffect_All
 * @author     Dan Jones <djones@rippleffect.com>
 */
class Rippleffect_All_Block_Catalog_Navigation extends Mage_Catalog_Block_Navigation {

    /**
     * Add an "All Items" menu item in standard category menu template.
     *
     * @param int Level number for list item class to start from
     * @param string Extra class of outermost list items
     * @param string If specified wraps children list in div with this class
     * @return string
     */
    public function renderCategoriesMenuHtml($level = 0, $outermostItemClass = '', $childrenWrapClass = '') {
        $html = parent::renderCategoriesMenuHtml();

        if (Mage::getStoreConfig('rippleffect/settings/all_products_menu_item')) {
            $page = Mage::getModel('cms/page')->load(Mage::getStoreConfig('rippleffect/settings/all_products_menu_item_page'), 'identifier');

            $menuHtml = '<li class="level0 %navid%">
<a href="' . substr(Mage::getUrl($page->getIdentifier()), 0, -1) . '">
<span>' . $page->getTitle() . '</span>
</a>
</li>';
            
            switch (Mage::getStoreConfig('rippleffect/settings/all_products_menu_item_page_position')) {
                case "before": {
                    $menuHtml = str_replace("%navid%", "nav-0 first", $menuHtml);
                    $html = str_replace("nav-1 first", "nav-1", $html);
                    $html = $menuHtml . $html;
                } break;
                case "after": {
                    preg_match_all("/nav-(\d*)/", $html, $matches);
                    $lastNavId = $matches[1][count($matches[1]) - 1];
                    $html = str_replace("nav-" . $lastNavId . " last", "nav-" . $lastNavId, $html);
                    $menuHtml = str_replace("%navid%", "nav-" . ($lastNavId + 1) . " last", $menuHtml);
                    $html = $html . $menuHtml;
                } break;
            }

        }
        
        return $html;
    }
}
