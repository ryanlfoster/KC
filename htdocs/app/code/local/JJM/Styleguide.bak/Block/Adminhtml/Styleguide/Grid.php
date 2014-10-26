<?php

class JJM_Styleguide_Block_Adminhtml_Styleguide_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("styleguideGrid");
				$this->setDefaultSort("styleguide_id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("styleguide/styleguide")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("styleguide_id", array(
				"header" => Mage::helper("styleguide")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "styleguide_id",
				));
                
				$this->addColumn("name", array(
				"header" => Mage::helper("styleguide")->__("Name"),
				"index" => "name",
				));
				$this->addColumn("item_content", array(
				"header" => Mage::helper("styleguide")->__("Content"),
				"index" => "item_content",
				));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('styleguide_id');
			$this->getMassactionBlock()->setFormFieldName('styleguide_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_styleguide', array(
					 'label'=> Mage::helper('styleguide')->__('Remove Styleguide'),
					 'url'  => $this->getUrl('*/adminhtml_styleguide/massRemove'),
					 'confirm' => Mage::helper('styleguide')->__('Are you sure?')
				));
			return $this;
		}
			

}