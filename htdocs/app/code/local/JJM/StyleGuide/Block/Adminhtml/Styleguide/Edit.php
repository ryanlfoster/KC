<?php
	
class JJM_Styleguide_Block_Adminhtml_Styleguide_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "styleguide_id";
				$this->_blockGroup = "styleguide";
				$this->_controller = "adminhtml_styleguide";
				$this->_updateButton("save", "label", Mage::helper("styleguide")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("styleguide")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("styleguide")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("styleguide_data") && Mage::registry("styleguide_data")->getId() ){

				    return Mage::helper("styleguide")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("styleguide_data")->getId()));

				} 
				else{

				     return Mage::helper("styleguide")->__("Add Item");

				}
		}

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
}