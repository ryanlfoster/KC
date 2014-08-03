<?php

class Rjs_StyleIcons_Block_Adminhtml_Entry_Renderer_Votes extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row) {
		$votes = Mage::getModel('styleicons/vote')->getCollection()->addFieldToFilter('entry',$row->getId());
		return $votes->getSize();
	}
}