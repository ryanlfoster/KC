<?php

exit("DO NOT RUN!");

$_SERVER['HTTP_HOST'] = "djones.kc2.dev.rippleffect.com";

include '../app/Mage.php';
Mage::app('admin')->setUseSessionInUrl(false);
Mage::getConfig()->init();

$csvPath = dirname(dirname(__FILE__)) . "/var/import/rs_product.csv";
$fh = fopen($csvPath, "r");
$rowcount = 0; $columns = array();


while ($row = fgetcsv($fh)) {
    $rowcount++;
    unset($regionId);
    
    if ($rowcount == 1) {
        // file headers
        $columns = $row;
        continue;
    }
    
    $row = _mapDataToArray($columns, $row);
    
    $product = Mage::getModel('catalog/product')->loadByAttribute('sku', "C" . $row['product_ref_code']);
    
    if (is_object($product)) {
        /* @var $product Mage_Catalog_Model_Product */
        
        if (substr($row['URL'],0,1) == "/") {
            $row['URL'] = substr($row['URL'],1);
        }
        
        $csv = array(
            "", // url_rewrite_id
            "1", // store_id
            uniqid(null, true),// id_path
            $row['URL'], // request_path
            $product->getUrlPath(), // target_path
            "0", // is_system
            "RP", // options
            "", // description
            "", // category_id
            $product->getEntityId(), // product_id
        );
        
        $str = '"' . implode('";"', $csv) . '"';
        
        echo str_replace('""',"NULL", $str) . "\n";
    }
    
}


function _mapDataToArray($columnsArray, $dataArray) {
    $finalArray = array();
    foreach ($columnsArray as $i => $key) {
        $finalArray[$key] = $dataArray[$i];
    }
    return $finalArray;
}
