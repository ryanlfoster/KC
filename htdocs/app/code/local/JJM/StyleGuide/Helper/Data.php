<?php
class JJM_Styleguide_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getStyleGuides() {
        $model = Mage::getModel('styleguide/styleguide');
    }

}
	 