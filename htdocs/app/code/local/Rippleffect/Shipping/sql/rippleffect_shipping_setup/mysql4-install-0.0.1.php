<?php

$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `{$this->getTable('rippleffect_shipping_methods')}`;
CREATE TABLE `{$this->getTable('rippleffect_shipping_methods')}` (
    `method_id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    `title` VARCHAR( 50 ) NOT NULL ,
    `conditions` TEXT NOT NULL ,
    `active` TINYINT( 1 ) NOT NULL ,
    UNIQUE (
        `title`
    )
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'Rippleffect Shipping Module Methods';
");