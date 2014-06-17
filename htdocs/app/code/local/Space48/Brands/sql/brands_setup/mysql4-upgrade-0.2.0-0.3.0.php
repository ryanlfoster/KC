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
    "ALTER TABLE space48_attribute_landing_page_products MODIFY position INT(11)");

$installer->endSetup();