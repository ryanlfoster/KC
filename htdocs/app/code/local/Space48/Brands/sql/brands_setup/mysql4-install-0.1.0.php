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

/*$installer->run(
    "DROP TABLE IF EXISTS {$this->getTable('space48_attribute_landing_page')};

     CREATE TABLE {$this->getTable('space48_attribute_landing_page')} (
      `id` int(4) NOT NULL AUTO_INCREMENT,
      `manufacturer_id` varchar(45) DEFAULT NULL,
      `title` varchar(45) DEFAULT NULL,
      `small_logo` varchar(45) DEFAULT NULL,
      `large_logo` varchar(45) DEFAULT NULL,
      `description` text,
      `meta_keywords` text,
      `meta_description` text,
      `status` int(11) DEFAULT NULL,
      `url_key` varchar(45) DEFAULT NULL,
      `meta_title` varchar(255) DEFAULT NULL,
     PRIMARY KEY (`id`)
     ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");*/

$installer->endSetup();