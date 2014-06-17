<?php
class Space48_Brands_Block_Adminhtml_Brands_Edit_Tab_Products extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Default controller constructor
     * Initialises the grid ID, Default sort column and default sort order.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('catalog_category_products');
        //$this->setDefaultSort('position');
        //$this->setDefaultDir('asc');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);

        // disable the un wanted functionality that we dont need.
        $this->setPagerVisibility(false);
        $this->setFilterValues(false);
        $this->setDefaultLimit(99999);
        $this->setSortableRows(false);
        $this->setFilterVisibility(false);

    }

    /**
     * Prepare Magento collection.
     *  Join Space 48 custom products table to show enabled products and store custom position for the
     *  brands product pages.
     *
     * @return this
     */
    protected function _prepareCollection()
    {
        $id     = $this->getRequest()->getParam('id');
        $brandId = Mage::helper('brands/brands')->getManufacturerIdByBrandId($id);

        $collection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('price')
            ->addAttributeToSelect('brand')
            ->addAttributeToFilter('brand', $brandId)
            ->addAttributeToFilter('type_id', 'configurable')
            ->joinField(
                'position',
                'catalog/category_product',
                'position',
                'product_id=entity_id',
                'category_id='.(int) $this->getRequest()->getParam('id', 0),
                'left'
            )
            ->joinField(
                'qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left'
            )
            ->joinField('entity_id',
                'products/products',
                '*',
                'product_id=entity_id',
                null,
                'inner'
            );

        $collection->getSelect()->order('at_entity_id.position', 'asc');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Product Name'),
            'index'     => 'name',
            'filter'    => false,
            'sortable'  => false
        ));

        $this->addColumn('sku', array(
            'header'    => Mage::helper('catalog')->__('Product SKU'),
            'index'     => 'sku',
            'filter'    => false,
            'sortable'  => false
        ));

        $this->addColumn('price', array(
            'header'        => Mage::helper('catalog')->__('Price'),
            'type'          => 'currency',
            'width'         => '1',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'         => 'price',
            'filter'        => false,
            'sortable'      => false
        ));

        $this->addColumn(
            'qty', array(
                'header'    => Mage::helper('catalog')->__('Stock Levels'),
                'type'      => 'number',
                'width'     => '100px',
                'index'     => 'qty',
                'filter'    => false,
                'sortable'  => false
            )
        );

        $this->addColumn('position', array(
            'header'    => Mage::helper('catalog')->__('Position'),
            'width'     => '1',
            'type'      => 'number',
            'index'     => 'position',
            'editable'  => true,
            'renderer'  => 'brands/adminhtml_brands_edit_tab_renderer_input',
            'filter'    => false,
            'sortable'  => false
        ));

        $this->addColumn('product_id', array(
            'header'    => Mage::helper('catalog')->__('Product Id'),
            'width'     => '1',
            'type'      => 'number',
            'index'     => 'product_id',
            'editable'  => false,
            'renderer'  => 'brands/adminhtml_brands_edit_tab_renderer_input',
            'filter'    => false,
            'sortable'  => false
        ));

        return parent::_prepareColumns();
    }

}