<?php

$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE IF NOT EXISTS `styleicons_competition` 
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `added` DATE DEFAULT NULL,
  `start_date` DATE DEFAULT NULL,
  `end_date` DATE DEFAULT NULL,
  `name` VARCHAR(255),
  `active` INT(11),
  PRIMARY KEY (`id`)
) 
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `styleicons_competition_entry` 
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `competition` INT(11) NOT NULL,
  `name` VARCHAR(255),
  `image` VARCHAR(255),
  `description` VARCHAR(255),
  `added` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`competition`) REFERENCES styleicons_competition(`id`) ON DELETE CASCADE
) 
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `styleicons_competition_winner` 
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `competition` INT(11) NOT NULL,
  `entry` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`competition`) REFERENCES styleicons_competition(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`entry`) REFERENCES styleicons_competition_entry(`id`) ON DELETE CASCADE
) 
ENGINE=INNODB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `styleicons_competition_entry_vote` 
(
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `entry` INT(11) NOT NULL,
  `ip` VARCHAR(255),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`entry`) REFERENCES styleicons_competition_entry(`id`) ON DELETE CASCADE
) 
ENGINE=INNODB DEFAULT CHARSET=utf8;
");

$installer->endSetup();