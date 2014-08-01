<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table styleguides(styleguide_id int not null auto_increment, name varchar(100) not null, content text not null, image1 varchar(100) not null, image2 varchar(100), image3 varchar(100), primary key(styleguide_id));


SQLTEXT;

$installer->run($sql);
//demo
//Mage::getModel('core/url_rewrite')->setId(null);
//demo
$installer->endSetup();
