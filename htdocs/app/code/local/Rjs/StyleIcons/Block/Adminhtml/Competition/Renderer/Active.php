<?php

class Rjs_StyleIcons_Block_Adminhtml_Competition_Renderer_Active extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row) {
		return ($row->getActive() == 1) ? 'Yes' : 'No';
	}
}