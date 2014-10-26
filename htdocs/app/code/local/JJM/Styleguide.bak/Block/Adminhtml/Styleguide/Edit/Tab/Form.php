<?php
class JJM_Styleguide_Block_Adminhtml_Styleguide_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("styleguide_form", array("legend"=>Mage::helper("styleguide")->__("Item information")));

				
						$fieldset->addField("name", "text", array(
						"label" => Mage::helper("styleguide")->__("Name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "name",
						));

						$fieldset->addField("item_content", "editor", array(
						"label" => Mage::helper("styleguide")->__("Content"),
						"class" => "required-entry",
						"required" => true,
						"name" => "content",
                        'style'     => 'height:15em',
                        'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
                        'wysiwyg'   => true,
						));

						$fieldset->addField('image1', 'image', array(
						'label' => Mage::helper('styleguide')->__('Image'),
						'name' => 'image1',
						'note' => '(*.jpg, *.png, *.gif)',
						));
						$fieldset->addField('image2', 'image', array(
						'label' => Mage::helper('styleguide')->__('Image'),
						'name' => 'image2',
						'note' => '(*.jpg, *.png, *.gif)',
						));
						$fieldset->addField('image3', 'image', array(
						'label' => Mage::helper('styleguide')->__('Image'),
						'name' => 'image3',
						'note' => '(*.jpg, *.png, *.gif)',
						));

				if (Mage::getSingleton("adminhtml/session")->getStyleguideData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getStyleguideData());
					Mage::getSingleton("adminhtml/session")->setStyleguideData(null);
				} 
				elseif(Mage::registry("styleguide_data")) {
				    $form->setValues(Mage::registry("styleguide_data")->getData());
				}
				return parent::_prepareForm();
		}
}
