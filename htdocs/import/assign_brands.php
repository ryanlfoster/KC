<?php

ini_set("memory_limit", "1024M");
set_time_limit(0);

include 'common.php';

include '../app/Mage.php';
Mage::app('admin')->setUseSessionInUrl(false);
Mage::getConfig()->init();

$products = Mage::getModel('catalog/product')->getCollection();

foreach ($products as $product) {
    echo "Product ID: " . $product->getId() . "\n";
    $pModel = Mage::getModel('catalog/product')->load($product->getId());
    
    $namePieces = explode(",", $pModel->getName());
    $brandName = trim($namePieces[0]);
    
    $optValue = getAttributeOptionValue("brand", $brandName);
    
    if (!$optValue) {
        echo "\tCreating option " . $brandName . "\n";
        $optValue = addAttributeOption("brand", $brandName);
    }
    
    if ($pModel->getBrand() != $optValue) {
        $pModel->setBrand($optValue);
        $pModel->save();
    }
    
    unset($pModel);
}
