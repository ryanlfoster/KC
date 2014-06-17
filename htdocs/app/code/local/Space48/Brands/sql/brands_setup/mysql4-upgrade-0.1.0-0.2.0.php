<?php
/**
 * Space 48 Attribute Landing Page Module
 *
 * @package Space48_Splash
 * @copyright Space48 ltd 2013
 * @company Space48
 * @author James Cowie
 * @link http://wiki.space48.com/modules/brands
 */
$installer = $this;

$installer->startSetup();

$installer->run(
    "DROP TABLE IF EXISTS {$this->getTable('space48_attribute_landing_page_products')};

     CREATE TABLE {$this->getTable('space48_attribute_landing_page_products')} (
      `id` int(4) NOT NULL AUTO_INCREMENT,
      `manufacturer_id` varchar(45) DEFAULT NULL,
      `product_id` varchar(45) DEFAULT NULL,
      `position` varchar(45) DEFAULT NULL,
     PRIMARY KEY (`id`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();