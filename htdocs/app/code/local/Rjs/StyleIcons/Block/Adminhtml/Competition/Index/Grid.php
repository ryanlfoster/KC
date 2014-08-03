<?php

class Rjs_StyleIcons_Block_Adminhtml_Competition_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct() {
        parent::__construct();
        $this->setId('styleicons_competition_grid');
        $this->setSaveParametersInSession(false);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('styleicons/competition')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {

        // $this->addColumn('added', array(
        //     'header'=> $this->__('Added'),
        //     'type' => 'datetime',
        //     'width' => '150px',
        //     'sortable' => true,
        //     'filter' => false,
        //     'index' => 'added',
        //     )
        // );

        $this->addColumn('name', array(
            'header'=> $this->__('Name'),
            'index' => 'name',
            )
        );

        $this->addColumn('start_date', array(
            'header'=> $this->__('Start Date'),
            'type' => 'datetime',
            'width' => '150px',
            'sortable' => true,
            'filter' => false,
            'index' => 'start_date',
            )
        );

        $this->addColumn('end_date', array(
            'header'=> $this->__('End Date'),
            'type' => 'datetime',
            'width' => '150px',
            'sortable' => true,
            'filter' => false,
            'index' => 'end_date',
            )
        );

        $this->addColumn('active', array(
            'header'=> $this->__('Active'),
            'type' => 'text',
            'width' => '150px',
            'sortable' => true,
            'filter' => false,
            'index' => 'active',
            'renderer' => new Rjs_StyleIcons_Block_Adminhtml_Competition_Renderer_Active()
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
                  'confirm' => 'Are you sure you want to delete this competition and all its entries?'
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