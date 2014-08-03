<?php

class Rjs_StyleIcons_Block_Adminhtml_Entry_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row) {

		$image = $row->getImage();

		if(file_exists($image)) {
			return '<img src="'.$image.'" title="'.$row->getName().'" />';
		} else {
			return 'No Image Found';
		}
	}
}