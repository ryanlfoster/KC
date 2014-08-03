<?php

class Rjs_StyleIcons_Block_Adminhtml_Entry_Renderer_Competition extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row) {

		$competition = Mage::getModel('styleicons/competition')->load($row->getCompetition());

		if($competition) {
			return $competition->getName();
		}
	}
}