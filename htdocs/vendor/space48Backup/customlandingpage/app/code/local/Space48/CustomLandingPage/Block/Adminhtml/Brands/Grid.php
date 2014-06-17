<?php
/**
 * Space48 Ltd
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.space48.com/license.html
 *
 * @category   Space48
 * @package    Space48_Custom_Landing_Page
 * @version    0.1.0
 * @copyright  Copyright (c) 2013-2013 Space48 Ltd. (http://www.space48.com)
 * @license    http://www.space48.com/license.html
 * @company    Space48
 * @author     James Cowie (james@space48.com), Matt Edwards (matt@space48.com)
 * @link       http://wiki.space48.com/modules/brands
 */
class Space48_CustomLandingPage_Block_Adminhtml_Brands_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $collection = Mage::getModel('customlandingpage/brands')->getCollection();
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
            'header'    => Mage::helper('customlandingpage')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('customlandingpage')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('url_key', array(
            'header'    => Mage::helper('customlandingpage')->__('URL'),
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