<?php

class Rippleffect_Shipping_Block_Adminhtml_Methods_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('methodsGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('rippleffect_shipping/method')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn('method_id', array(
            'header'  => Mage::helper('rippleffect_shipping')->__('ID #'),
            'width'   => '80px',
            'type'    => 'text',
            'index'   => 'method_id'
        ));

        $this->addColumn('title', array(
            'header'  => Mage::helper('rippleffect_shipping')->__('Title'),
            'width'   => '80px',
            'type'    => 'text',
            'index'   => 'title'
        ));

        $this->addColumn('active', array(
            'header'  => Mage::helper('rippleffect_shipping')->__('Active'),
            'width'   => '80px',
            'index'   => 'active',
            'type'    => 'options',
            'options' => array(0 => 'No', 1 => 'Yes')
        ));

        return parent::_prepareColumns();
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/edit', array('_current'=>true));
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function  _toHtml() {
        return parent::_toHtml();
    }
}
