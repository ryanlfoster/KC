<?php

$installer = $this;

$connection = $installer->getConnection();

$installer->startSetup();

$roles = array();
$roles['bronze'] = array(
    "admin/sales/order/actions/create" => false,
);
$roles["silver"] = array_merge($roles['bronze'], array());
$roles["gold"] = array_merge($roles['silver'], array());

Mage::getModel('rippleffect/roles_installer')->installRoles($roles);

$installer->endSetup();
