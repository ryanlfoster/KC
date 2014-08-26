<?php

/**
 * create celeb style guide table
 */
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table styleguides(styleguide_id int not null auto_increment, name varchar(100) not null UNIQUE, content text not null, image1 varchar(100) not null, image2 varchar(100), image3 varchar(100), primary key(styleguide_id));


SQLTEXT;

try {
    $installer->run($sql);
} catch (Exception $e) {
    Mage::log('Styleguide base table install fail: '.$e);
}


$sql = "CREATE TABLE `styleguide_products` (
  `id` int(11) int not null auto_increment,
  `styleguide_id` int(11) NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

try {
    $installer->run($sql);
} catch (Exception $e) {
    Mage::log('Styleguide products table install fail: '.$e);
}


///**
// * Install product link types
// */
//$data = array(
//    array(
//        'link_type_id'  => JJM_Styleguide_Model_Catalog_Product_Link::LINK_TYPE_CUSTOM,
//        'code'          => 'custom'
//    )
//);
//foreach ($data as $bind) {
//    $installer->getConnection()->insertForce($installer->getTable('catalog/product_link_type'), $bind);
//}
///**
// * Install product link attributes
// */
//$data = array(
//    array(
//        'link_type_id'                  => JJM_Styleguide_Model_Catalog_Product_Link::LINK_TYPE_CUSTOM,
//        'product_link_attribute_code'   => 'position',
//        'data_type'                     => 'int'
//    )
//);
//$installer->getConnection()->insertMultiple($installer->getTable('catalog/product_link_attribute'), $data);
//

//demo
//Mage::getModel('core/url_rewrite')->setId(null);
//demo
$installer->endSetup();
