<?php
/**
 * Brands module import script to assign all products to the bespoke table require for merchandising.
 *
 */
require_once('app/Mage.php');
Mage::app();
// require as this is a heavy lifting script.
ini_set('memory_limit', '2048M');

$products = Mage::getModel('catalog/product')->getCollection();
$products->addAttributeToSelect("*");
$write = Mage::getSingleton('core/resource')->getConnection('core_write');

foreach ($products as $product) {
    if ($product->getTypeId() == 'configurable') {
        $model = Mage::getModel('products/products')->getCollection();
        $model->addFieldToFilter('product_id', array('eq' => $product->getId()));

        if (!$model->getData()) {
            // product doesnt exist in lookup table so lets add it in :)
            $write->query("INSERT INTO
                            space48_attribute_landing_page_products
                                (manufacturer_id, product_id, position)
                            VALUES ('" . $product->getBrand() . "', '" . $product->getId() . "', 999999) ");
        }
    }

}
