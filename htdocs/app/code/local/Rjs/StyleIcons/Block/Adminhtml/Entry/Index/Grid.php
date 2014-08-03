<?php

class Rjs_StyleIcons_Block_Adminhtml_Entry_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('styleicons_entry_grid');
        $this->setSaveParametersInSession(false);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('styleicons/entry')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        $this->addColumn('added', array(
            'header'=> $this->__('Added'),
            'type' => 'datetime',
            'width' => '150px',
            'sortable' => true,
            'filter' => false,
            'index' => 'added',
            )
        );

        $this->addColumn('name', array(
            'header'=> $this->__('Name'),
            'index' => 'name',
            )
        );

        $this->addColumn('competition', array(
            'header'=> $this->__('Competition'),
            'index' => 'season',
            'renderer' => new Rjs_StyleIcons_Block_Adminhtml_Entry_Renderer_Competition()
            )
        );

        $this->addColumn('image', array(
            'header'=> $this->__('Image'),
            'renderer' => new Rjs_StyleIcons_Block_Adminhtml_Entry_Renderer_Image()
            )
        );
        $this->addColumn('votes', array(
            'header'=> $this->__('Votes'),
            'renderer' => new Rjs_StyleIcons_Block_Adminhtml_Entry_Renderer_Votes()
            )
        );

        $this->addColumn('action', array(
            'header' => Mage::helper('styleicons')->__('Action'),
            'width' => '50px',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
              array(
                  'caption' => Mage::helper('styleicons')->__('Delete'),
                  'url' => array('base'=> '*/*/delete'),
                  'field' => 'id',
                  'confirm' => 'Are you sure you want to delete this entry?'
                  )
              ),
            'filter' => false,
            'sortable' => false,
            'index' => 'id',
            'is_system' => true
            )
        );

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}