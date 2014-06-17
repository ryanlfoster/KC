<?php

class migrationApi {
    public $proxy;
    public $sessionId;
 
    public function __construct(){
        $this->proxy = new SoapClient('http://djones.kc2.dev.rippleffect.com/api/soap/?wsdl');
        $this->sessionId = $this->proxy->login('shopbaseimport', 'WJoo2ClKYe');
    }
    /**
     * creates product in database using API
     *
     * @param string $productType
     * @param integer $setId
     * @param string $sku
     * @param array $productData
     * @return array
     */
    public function createProduct($productType, $setId, $sku, $productData) {
        $result = array();
 
        try {
            $productId = $this->proxy->catalogProductCreate($this->sessionId, array($productType, $setId, $sku, $productData));
            //$productId = $this->proxy->call($this->sessionId, 'product.create', array($productType, $setId, $sku, $productData));
        }
        catch (Exception $e) {
            print_r($e);
        }
/*
        catch (SoapFault $s) {
            print_r($s);
            exit;
        }
*/

        $result = $this->proxy->call($this->sessionId, 'product.info', $productId); 
 
        return $result;
    }
 
    public function updateProduct($sku, $params) {
        return $this->proxy->call($this->sessionId, 'catalog_product.update', array($sku, $params));
    }
    /**
     * List product attributes
     *
     * @return array
     */
    public function getAttributes() {
        return $this->proxy->call($this->sessionId, 'product_attribute_set.list');
    }
    //this function translates values from my starting array - $product into API fields, you can define your own.
    public function setProductAttributes($product) {
        $newProductData = array();
 
        $newProductData['name'] = $product['name'];
 
        $newProductData['price'] = $product['price'];
 
        $newProductData['description'] = $product['description'];
        $newProductData['short_description'] = $product['short_description'];
 
        $newProductData['weight'] = $product['weight'] ? $product['weight'] : 0;
        $newProductData['categories']   = array();
        $newProductData['tax_class_id'] = $product['tax_class_id'] ? $product['tax_class_id'] : 0;
        $newProductData['status'] = 1;//active
 
        ///////////////////////////////////////////////////////////////////
        $newProductData['qty'] = $product['qty'] ? $product['qty'] : 99999;//quantity - set it here
 
        /**
         * Stock configuration
         */
        $newProductData['stock_data'] = array (
            'min_sale_qty'              => 1,
            'use_config_min_sale_qty'   => 1,
            'use_config_max_sale_qty'   => 1,
            'use_config_manage_stock'   => 1,
            'qty'                       => $newProductData['qty'],
            'is_in_stock'               => 1
        );
        return $newProductData;
    }
 
    public function getProduct($sku) {
        return $this->proxy->call($this->sessionId, 'product.info', $sku);
    }
}
