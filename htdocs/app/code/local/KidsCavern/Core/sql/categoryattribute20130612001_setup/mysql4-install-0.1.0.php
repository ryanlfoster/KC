<?php
/**
 * Add new attribute(s) against category in Magento
 *
 * PHP Version 5
 *
 * @category Setup_SQL
 * @package  Aphrodite_Core
 * @author   George Schiopu <george@space48.com>
 * @license  MIT http://www.google.com
 * @version  GIT: 0.1.0
 * @link     http://www.space48.com
 */

$installer = $this;
$installer->startSetup();

/**
 *  Category attribute that holds the "from price" value
 */

$installer->addAttribute(
    "catalog_product",
    "product_flash",
    array(
        "type" => 'int',
        "group" => 'Images',
        "label" => 'Product Flash',
        "input" => 'select',
        "global" => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        "visible" => true,
        "used_in_product_listing" => true,
        "required" => false,
        "user_defined" => true,
        "searchable" => false,
        "filterable" => false,
        "comparable" => false,
        "visible_on_front" => true,
        "unique" => false,
        "note" => '',
        )
);

$installer->endSetup();
