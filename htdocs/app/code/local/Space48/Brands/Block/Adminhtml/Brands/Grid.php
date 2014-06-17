<?php
class Space48_Brands_Block_Adminhtml_Brands_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Default controller constructor
     * Initialises the grid ID, Default sort column and default sort order.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('brandsGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
    }

    /**
     * Loads the entire collections of available brands and returns for display within the grid,
     *
     * @return this
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('brands/brands')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Creates the columns that are visible within the grid as well as presentation of how they
     * should be displayed.
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('brands')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('brands')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('url_key', array(
            'header'    => Mage::helper('brands')->__('URL'),
            'align'     => 'left',
            'index'     => 'url_key'
        ));

        /*$this->addColumn('status', array(
            'header'    => Mage::helper('customlandingpage')->__('Status'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'status',
        ));*/

        return parent::_prepareColumns();
    }

    /**
     * Helper function to get the ID of each for so when selected the edit action can be
     * triggered correctly.
     *
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}