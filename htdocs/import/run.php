<?php

exit("DO NOT RUN!");

include "common.php";
include '../app/Mage.php';
Mage::app('admin')->setUseSessionInUrl(false);
Mage::getConfig()->init();

$csvPath = dirname(dirname(__FILE__)) . "/var/import/products4.csv";
$fh = fopen($csvPath, "r");
$rowcount = 0; $columns = array();

$configurable_products = array();
$simple_products = array();
while ($row = fgetcsv($fh)) {
    $rowcount++;
    if ($rowcount == 1) {
        // file headers
        $columns = $row;
        continue;
    }
    
    $data = _mapDataToArray($columns, $row);
    
    if ($data['is_configurable'] == "0" || $data['is_configurable'] == "1") {
        $configurable_products[$data['id']] = $data;
    }
    else {
        if (!is_array($simple_products[$data['configurable_product_id']])) {
            $simple_products[$data['configurable_product_id']] = array();
        }
        $simple_products[$data['configurable_product_id']][$data['variation_id']] = $data;
    }
}

foreach ($configurable_products as $id => $data) {
    if ($data['enabled'] == "0") {
        continue;
    }

    $magento_categories = array();
    foreach (explode(",", $data['categories']) as $k => $cat) {
        $brand = $_brandAttributeMap[$cat]['brand'];
        if (isset($_specialCategoryMap[$cat])) {
            array_push($magento_categories, $_specialCategoryMap[$cat]);
        }
        else {
            $tlCat = $_brandAttributeMap[$cat]['top_category'];
            $catId = $_productTypeCategoryMap[$data['product_type']][$tlCat];
            array_push($magento_categories, $catId);
        }
    }

    if ($data['is_configurable'] == "1") {
        if (substr($data['size_chart'], 0, 1) == "S") {
            $configurable_attribute = "shoe_size";
        }
        else {
            $configurable_attribute = "size";
        }

        if (substr($data['size_chart'], 0, 1) == "S") {
            $configurable_attribute = "shoe_size"; $attr_id = 128;
        }
        else {
            $configurable_attribute = "size"; $attr_id = 134;
        }

        $config_price = 999999; $simpleProducts = array();
        foreach ($simple_products[$data['id']] as $id => $simple_data) {
            if ($simple_data['size'] != "") {
                $attr_value = $simple_data['size']; $attr_id = 134; 
            }
            else if ($simple_data['shoe_size'] != "") {
                $attr_value = $simple_data['shoe_size']; $attr_id = 128;
            }
            
            
            // normalize
            $find = array(); $replace = array();
            
            $find[] = "mths";       $replace[] = " Months";
            $find[] = " mths";      $replace[] = " Months";
            $find[] = "mths.";      $replace[] = " Months";
            $find[] = " Months.";   $replace[] = " Months";
            $find[] = "months";     $replace[] = "Months";
            $find[] = "yrs";        $replace[] = " Years";
            $find[] = " Years.";    $replace[] = " Years";
            $find[] = "years";      $replace[] = "Years";
            
            $attr_value = str_replace($find, $replace, $attr_value);
            
            $attr_value = trim($attr_value);
            
            $configurableAttributeOptionId = getAttributeOptionValue($configurable_attribute, $attr_value);
            if (!$configurableAttributeOptionId) {
                $configurableAttributeOptionId = addAttributeOption($configurable_attribute, $attr_value);
            }

            $testProduct = Mage::getModel('catalog/product')->loadByAttribute("sku", $simple_data['sku']);
            if (is_object($testProduct)) {
                echo "Product " . $simple_data['sku'] . " already exists. Skipping..\n";
                unset($testProduct);
                continue;
            }

            echo "Creating product " . $simple_data['sku'] . "\n";
            $sProduct = Mage::getModel('catalog/product');
            $sProduct
                ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_SIMPLE)
                ->setWebsiteIds(array(1))
                ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE)
                ->setTaxClassId(5)
                ->setAttributeSetId($_attributeSetMap[$data['product_type']]['attribute_set'])
                ->setCategoryIds($magento_categories)
                ->setSku($simple_data['sku'])
                ->setName($data['product_name'] . " - " . $attr_value)
                ->setShortDescription($data['short_description'])
                ->setDescription($data['long_description'])
                ->setPrice(sprintf("%0.2f", $simple_data['price']))
                ->setData($configurable_attribute, $configurableAttributeOptionId)
            ;

            if ($simple_data['age_group'] != "") {
                $simple_data['age_group'] = trim($simple_data['age_group']);
                
                $ageRangeOptionId = getAttributeOptionValue("age_range", $simple_data['age_group']);
                if (!$ageRangeOptionId) {
                    $ageRangeOptionId = addAttributeOption("age_range", $simple_data['age_group']);
                }

                $sProduct->setAgeRange($ageRangeOptionId);
            }
            $sProduct->setStockData(array(
                'is_in_stock' => 1,
                'qty' => 99999
            ));

            $sProduct->save();

            if ($simple_data['price'] < $config_price) {
                $config_price = $simple_data['price'];
            }

            $inArray = false;
            foreach ($simpleProducts as $arr) {
                if ($arr['id'] == $sProduct->getId()) {
                    $inArray = true;
                    break;
                }
            }
            
            if (!$inArray) {
                array_push(
                    $simpleProducts,
                    array(
                        "id" => $sProduct->getId(),
                        "price" => $sProduct->getPrice(),
                        "attr_code" => $configurable_attribute,
                        "attr_id" => $attr_id,
                        "value" => $configurableAttributeOptionId,
                        "label" => $attr_value
                    )
                );
            }
            
            // free up some memory
            unset($sProduct);
        } // end foreach ($simple_products[$data['id']] as $id => $simple_data)

        echo "Creating configurable product C" . $data['product_ref_code'] . "\n";

        $cProduct = Mage::getModel('catalog/product');
        $cProduct
            ->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE)
            ->setTaxClassId(5)
            ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
            ->setStatus(Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->setWebsiteIds(array(1))
            ->setCategoryIds($magento_categories)
            ->setAttributeSetId($_attributeSetMap[$data['product_type']]['attribute_set'])
            ->setSku("C" . $data['product_ref_code'])
            ->setName($data['product_name'])
            ->setShortDescription($data['short_description'])
            ->setDescription($data['long_description'])
            ->setPrice(sprintf("%0.2f", $config_price))
            ->setUrlKey(getProductUrlKey($data['product_name']))
        ;

        $sizingChartValue = getAttributeOptionValue("sizing_chart", $data['size_chart']);
        if ($sizingChartValue) {
            $cProduct->setData("sizing_chart", $sizingChartValue);
        }

        $cProduct->setCanSaveConfigurableAttributes(true);
        $cProduct->setCanSaveCustomOptions(true);

echo "\tUsed product attribute id: " . $_attributeIds[$configurable_attribute] . " (" . $configurable_attribute . ")\n";

        $cProductTypeInstance = $cProduct->getTypeInstance();
        $cProductTypeInstance->setUsedProductAttributeIds(array($_attributeIds[$configurable_attribute]));

        $attributes_array = $cProductTypeInstance->getConfigurableAttributesAsArray();
        foreach($attributes_array as $key => $attribute_array) {
            $attributes_array[$key]['use_default'] = 1;
            $attributes_array[$key]['position'] = 0;

            if (isset($attribute_array['frontend_label'])) {
                $attributes_array[$key]['label'] = $attribute_array['frontend_label'];
            }
            else {
                $attributes_array[$key]['label'] = $attribute_array['attribute_code'];
            }
        }

        $cProduct->setConfigurableAttributesData($attributes_array);

        $dataArray = array();
        foreach ($simpleProducts as $simpleArray) {
            $dataArray[$simpleArray['id']] = array();
            foreach ($attributes_array as $attrArray) {
                array_push(
                    $dataArray[$simpleArray['id']],
                    array(
                        "attribute_id" => $simpleArray['attr_id'],
                        "label" => $simpleArray['label'],
                        "is_percent" => false,
                        "pricing_value" => $simpleArray['price']
                    )
                );
            }
        }

        $cProduct->setConfigurableProductsData($dataArray);

        $cProduct->setStockData(array(
            'use_config_manage_stock' => 1,
            'is_in_stock' => 1,
            'is_salable' => 1
        ));

        $cProduct->save();

        $cProduct = Mage::getModel('catalog/product')->load($cProduct->getId());

        _addImagesToProduct($data, $cProduct);

        $cProduct->save();

        unset($cProduct);

    } // end if ($data['is_configurable'] == "1")

} // end foreach ($configurable_products as $id => $data)























exit;
include "common.php";
include "migrationApi.php";

$api = new migrationApi();
$attrSets = array();
foreach ($api->getAttributes() as $arr) {
    $attrSets[$arr['set_id']] = $arr['name'];
}



$productsToAdd = array();

foreach ($configurable_products as $id => $data) {
    if ($data['enabled'] == "0") {
        continue;
    }

    $magento_categories = array();
    foreach (explode(",", $data['categories']) as $k => $cat) {
        $brand = $_brandAttributeMap[$cat]['brand'];
        if (isset($_specialCategoryMap[$cat])) {
            array_push($magento_categories, $_specialCategoryMap[$cat]);
        }
        else {
            $tlCat = $_brandAttributeMap[$cat]['top_category'];
            $catId = $_productTypeCategoryMap[$data['product_type']][$tlCat];
            array_push($magento_categories, $catId);
        }
    }

    if ($data['is_configurable'] == "1") {
        $productToAdd = array();

        if (substr($data['size_chart'], 0, 1) == "S") {
            $configurable_attribute = "shoe_size"; $attr_id = 128;
        }
        else {
            $configurable_attribute = "size"; $attr_id = 134;
        }

        $productToAdd = array(
            "name" => $data['product_name'],
            "sku" => "C" . $data['product_ref_code'],
            "variants" => array(),
            "set_id" => $_attributeSetMap[$data['product_type']]['attribute_set'],
            "short_description" => $data['short_description'],
            "description" => $data['long_description'],
            "categories" => array($magento_categories),
            "attribute_id" => $attr_id,
            "attribute_code" => $configurable_attribute,
            "tax_class_id" => 5
        );

        $config_price = 999999;
        foreach ($simple_products[$data['id']] as $id => $simple_data) {

            if ($simple_data['size'] != "") {
                $attr_value = $simple_data['size'];
            }
            else if ($simple_data['shoe_size'] != "") {
                $attr_value = $simple_data['shoe_size'];
            }

            // normalize
            $find = array(); $replace = array();
            
            $find[] = "mths";       $replace[] = " Months";
            $find[] = " mths";      $replace[] = " Months";
            $find[] = "mths.";      $replace[] = " Months";
            $find[] = " Months.";   $replace[] = " Months";
            $find[] = "months";     $replace[] = "Months";
            $find[] = "yrs";        $replace[] = " Years";
            $find[] = " Years.";    $replace[] = " Years";
            $find[] = "years";      $replace[] = "Years";
            
            $attr_value = str_replace($find, $replace, $attr_value);
            
            $attr_value = trim($attr_value);

            $productToAdd['variants'][] = 
                array(
                    "sku" => $simple_data['sku'],
                    "price" => sprintf("%0.2f", $simple_data['price']),
                    "attribute_value" => $attr_value
                );

            if ($simple_data['price'] < $config_price) {
                $config_price = $simple_data['price'];
            }
        }

        $productToAdd['price'] = sprintf("%0.2f", $config_price);

        print_r($productToAdd);

        $configurableProductsData = array();
        $createdProducts = array();
        $newProductData = $api->setProductAttributes($productToAdd);
        foreach ($productToAdd['variants'] as $variant) {
            echo "adding variant (" . $variant['sku'] . ")\n";
            // call api to add simple product
            $createdProduct = 
                $api->createProduct(
                    'simple',
                    $productToAdd['set_id'],
                    $variant['sku'],
                    array_merge(
                        $newProductData,
                        array(
                            "name" => $productToAdd['name'] . "-" . $variant['attribute_value'],
                            "price" => $variant['price'],
                            $productToAdd['attribute_code'] => $variant[$productToAdd['attribute_value']],
                            "visibility" => 1
                        )
                    )
                );

            //prepare array for configurable
            $configurableProductsData[$createdProduct['product_id']] = array(
                'attribute_id'      => $productToAdd['attribute_id'], //The attribute id
                'label'             => $attrSets[$productToAdd['set_id']],
                //'value_index'       => $colorMap[$color], //option ID, map something like 'brown'=>10, 'red'=>1 etc
                'is_percent'        => 0,
                'pricing_value'     => ''
            );
            $createdProducts[] = $createdProduct;
        }

        $configurableAttributesData = array(
            array(
                'id'                => NULL,
                'position'          => NULL,
                'values'            => $configurableProductsData,
                'attribute_id'      => $productToAdd['attribute_id'],
                'attribute_code'    => 'color',
                'html_id'           => 'config_super_product__attribute_0'
            )
        );

        unset($newProductData['stock_data']);
        $newProductData['name'] = $productToAdd['name'];
        $newProductData['meta_title'] = $productToAdd['name'];
        $newProductData['color'] = 0;
        $newProductData['configurable_products_data']   = $configurableProductsData;
        $newProductData['configurable_attributes_data'] = $configurableAttributesData;
        $newProductData['categories']   = $productToAdd['categories'];
        $newProductData['visibility'] = 4;

print_r($newProductData);

        //and add product
        echo "adding configurable\n";
        $result = $api->createProduct('configurable', $productToAdd['set_id'], $product['sku'], $newProductData);

        exit;

    }

}




exit;

$products = array(
array('name'=>'product 1', 'color'=>'brown;green', 'product_sku'=>'uniqueValue1'),
array('name'=>'product 2', 'color'=>'red;yellow', 'product_sku'=>'uniqueValue2'),
);
 
if(count($products)) {
 
    foreach ($products as $product) {
        if(trim($product['product_sku'])!='') {
 
            $productColors = explode(';', $product['color']);
//so here we have 'brown;green;yellow' for example
 
            $createdProducts = array();
            $configurableProductsData = array();
 
            //create simple products here for each color
            foreach ($productColors as $color) {
                $productSKU = trim($product['product_sku'].'_'.str_replace(' ', '_', $color));
                //translate $product array into API field map
                $newProductData = $api->setProductAttributes($product, $color);
                $createdProduct = $api->createProduct('simple', $set['set_id'], $productSKU, $newProductData);
 
                //prepare array for configurable
                $configurableProductsData[$createdProduct['product_id']] = array(
                        'attribute_id'      => $colorAttrId, //The attribute id
                        'label'             => $color,
                        'value_index'       => $colorMap[$color], //option ID, map something like 'brown'=>10, 'red'=>1 etc
                        'is_percent'        => 0,
                        'pricing_value'     => ''
                );
                $createdProducts[] = $createdProduct;
            }
//          Create the configurable attributes data
            $configurableAttributesData = array(
                array(
                    'id'                => NULL,
                    'label'             => '', //optional, will be replaced by the modified api.php
                    'position'          => NULL,
                    'values'            => $configurableProductsData,
                    'attribute_id'      => $colorAttrId,
                    'attribute_code'    => 'color',
                    'frontend_label'    => '', //optional, will be replaced by the modifed api.php
                    'html_id'           => 'config_super_product__attribute_0'
                )
            );
 
//          add configurable product
            //reset some values for groupping product
            unset($newProductData['stock_data']);
            $newProductData['name'] = $product['name'];
            $newProductData['meta_title'] = $product['name'];
            $newProductData['color'] = 0;
            $newProductData['configurable_products_data']   = $configurableProductsData;
            $newProductData['configurable_attributes_data'] = $configurableAttributesData;
            $newProductData['categories']   = array(3, 4);//(selected from database, use your own)
            //and add product
            $result = $api->createProduct('configurable', $set['set_id'], $product['product_sku'], $newProductData);
        }
    }
}
