#!/usr/bin/php
<?php
/* ================================ */
/* functions used in this feed */
function addProductToCsvFile($productData, $handle)  {
		if(!empty($productData))
		{
			foreach ($productData as $k => $val) {
				$bad = array("°", "“", "•", "’", "”", "\r\n", "\n", "\r", "\t", "Â", "  ", " .", "Ã", '"', "&#160;");
				$good = array("degree", "''", "-", "'", "''", ". ", ". ", ". ", "", " ", " ", ".", " ", "&quot;", "");
				if ($k != 'shipping') {
					$data_str = str_replace($bad, $good, $val);
					$str_srch=array("!.","?.","  ","..","...");
					$str_rplc=array("!","?"," ",".",".");
					$productData[$k] = str_replace($str_srch,$str_rplc,$data_str);
				}
			}
			$csvline = join("\t",$productData);
			fwrite($handle,$csvline."\r\n");
		}
}
/* ================================ */

if(isset($_SERVER['SHELL']) && !empty($_SERVER['SHELL'])) {
	$approot = dirname(dirname(__file__));
	require_once( $approot.'/app/Mage.php' );
	Mage::app('default');		// Change here for other site(s)
	
	@ini_set('max_execution_time', '0');
	@ini_set('max_input_time', '0');
	@set_time_limit(0);
	@ignore_user_abort(true);
	@ini_set('memory_limit','6G');

	$feedDir  = $approot.'/feeds/';
	$feedFile = "gbase_kidscavern.txt";
	

        
	$visibility = array(
		Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
		Mage_Catalog_Model_Product_Visibility::VISIBILITY_IN_CATALOG
	);

	$_products = Mage::getModel('catalog/product')
				->getCollection()
                                ->addAttributeToSelect('*')
				->addAttributeToFilter('status', 1)
				->addAttributeToFilter('visibility', $visibility);
	
	if (!file_exists($feedDir)) {
		mkdir($feedDir, 0777);
	}
	
	$handle = fopen($feedDir.$feedFile, 'w');
	$prodCount = 0;
	
	$heading = array('id','title','brand','description','product_type','link','image_link','condition','price','availability','shipping', 'mpn', 'gender','size');
	$feed_line = implode("\t", $heading) . "\r\n";
	fwrite($handle, $feed_line);
	
	foreach($_products as $_product)
	{
		$productData = array(); 
		foreach($_product->getCategoryIds() as $categoryId) 
		{
			$category = Mage::getModel('catalog/category')->load($categoryId);  
			if ($lowestCategory == NULL || $category->getLevel() > $lowestCategory->getLevel()) {
				$lowestCategory = $category;
			}
		}

		$categoryTree = array();
		if (!is_null($lowestCategory)) {
			do {
				array_unshift($categoryTree , $lowestCategory->getName() );    
				$lowestCategory = $lowestCategory->getParentCategory();  
			} while ( $lowestCategory->getLevel() > 1);
			$ourCategory = implode(' > ',$categoryTree);
		}

		if( $_product->getTypeId() == 'configurable' ) {
			
                        // show configurable product 
                        $productData['id']              = $_product->getSku();
                        $productData['title']           = $_product->getName();
                        $productData['brand']           = $_product->getAttributeText('brand');
                        $productData['description']     = substr(trim(strip_tags($_product->getShortDescription())),0,10000);
                        $productData['product_type']    = $ourCategory;
                        $productData['url']             = Mage::getBaseUrl().$_product->getUrlKey().'.html';
                        $productData['image_link']      = Mage::helper('catalog/image')->init($_product, 'thumbnail')->resize(350,350);//?trim(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $_chProd
                        $productData['condition']       = 'new';

                        if ($_product->getSpecialPrice()) {
                            $tempPrice = $_product->getSpecialPrice() . ' GBP';
                        } else {
                            $tempPrice = $_product->getPrice() . ' GBP';
                        }

                        // check to see if a tax class is VAT Zero
                        if($_product['tax_class_id'] == 5) {
                            $productData['price'] = $tempPrice;
                        } else {
                            $productData['price'] = round($tempPrice*1.20, 2);
                        }

                        $sql            = "SELECT is_in_stock FROM cataloginventory_stock_item WHERE product_id = '" . $_product->getId() . "'";
                        $resource       = Mage::getSingleton('core/resource');
                        $readConnection = $resource->getConnection('core_read');
                        $row            = $readConnection->fetchAll($sql);

                        if ($row[0]['is_in_stock'] == 0) {
                            $stockLevel = 'out of stock';
                        } else {
                            $stockLevel = 'in stock';
                        }

                        $productData['availability'] = $stockLevel;
                        $productData['shipping']	 ='GB:::0.00 GBP';
                        $productData['mpn']          = $_product->getSku();
                        $productData['gender']       = $_product->getAttributeText('gender');
                        $productData['size']         = $_product->getAttributeText('size');

                        addProductToCsvFile($productData, $handle);
                        $prodCount++;
                    
                    
                        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $_product);

			foreach($childProducts as $_chProd) {
                            if ($_chProd->getVisibility() != 1) {
                                $productData['id']		  = $_chProd->getSku();
                                $productData['title']	  = $_chProd->getName();
                                $productData['brand']	  = $_chProd->getAttributeText('brand');
                                $productData['description']	  = substr(trim(strip_tags(str_replace("\t"," ",$_chProd->getShortDescription()))),0,1000);
                                $productData['product_type']  = $ourCategory;
                                $productData['url']		  = Mage::getBaseUrl().$_product->getUrlKey().'.html';
                                $productData['image_link']	  = Mage::helper('catalog/image')->init($_chProd, 'thumbnail')->resize(350,350);
                                $productData['condition']	  = 'new';

                                if ($_chProd->getSpecialPrice()) {
                                    $tempPrice = $_chProd->getSpecialPrice() . ' GBP';
                                } else {
                                    $tempPrice = $_chProd->getPrice() . ' GBP';
                                }

                                // check to see if a tax class is VAT Zero
                                if($_chProd['tax_class_id'] == 5) {
                                    $productData['price'] = $tempPrice;
                                } else {
                                    $productData['price'] = round($tempPrice*1.20, 2);
                                }

                                $sql            = "SELECT is_in_stock FROM cataloginventory_stock_item WHERE product_id = '" . $_chProd->getId() . "'";
                                $resource       = Mage::getSingleton('core/resource');
                                $readConnection = $resource->getConnection('core_read');
                                $row            = $readConnection->fetchAll($sql);

                                if ($row[0]['is_in_stock'] == 0) {
                                        $stockLevel = 'out of stock';
                                } else {
                                        $stockLevel = 'in stock';
                                }

                                $productData['availability'] = $stockLevel;
                                $productData['shipping']     = 'GB:::0.00 GBP';
                                $productData['mpn']          = $_chProd->getSku();
                                $productData['gender']       = $_chProd->getAttributeText('gender');
                                $productData['size']         = $_chProd->getAttributeText('size');
                                addProductToCsvFile($productData, $handle);
                                $prodCount++;
                            }
                        }
                        
		} elseif( $_product->getTypeId() == 'grouped' ) {
			$childProducts = $_product->getTypeInstance(true)->getAssociatedProducts($_product);
			foreach($childProducts as $_chProd)
			{
				$childProduct                = Mage::getModel('catalog/product')->load($_chProd->getId());
				$productData['id']		     = $childProduct->getSku();
				$productData['title']	     = $childProduct->getName();
				$productData['brand']	     = $childProduct->getAttributeText('brand');
				$productData['description']	 = substr(trim(strip_tags($childProduct->getShortDescription())),0,10000);
				$productData['product_type'] = $ourCategory;
				$productData['url']		     = Mage::getBaseUrl().$_product->getUrlKey().'.html';
				$productData['image_link']	 = Mage::helper('catalog/image')->init($childProduct, 'thumbnail')->resize(350,350);
				$productData['condition']	 = 'new';

				if ($childProduct->getSpecialPrice()) {
					$tempPrice = $childProduct->getSpecialPrice() . ' GBP';
				} else {
					$tempPrice = $childProduct->getPrice() . ' GBP';
				}

                // check to see if a tax class is VAT Zero
                if($childProduct['tax_class_id'] == 5) {
                    $productData['price'] = $tempPrice;
                } else {
                    $productData['price']	= round($tempPrice*1.20, 2);
                }

				$sql            = "SELECT is_in_stock FROM cataloginventory_stock_item WHERE product_id = '" . $childProduct->getId() . "'";
				$resource       = Mage::getSingleton('core/resource');
				$readConnection = $resource->getConnection('core_read');
				$row            = $readConnection->fetchAll($sql);

				if ($row[0]['is_in_stock'] == 0) {
					$stockLevel = 'out of stock';
				} else {
					$stockLevel = 'in stock';
				}

				$productData['availability'] = $stockLevel;
				$productData['shipping']	 ='GB:::0.00 GBP';
				$productData['mpn']          = $childProduct->getSku();
                $productData['gender']       = $childProduct->getAttributeText('gender');
                $productData['size']         = $childProduct->getAttributeText('size');

				addProductToCsvFile($productData, $handle);
				$prodCount++;
			}
		} else {
                    
                    /* don't add to feed if product visibility is set to
                     * "Not Visible Individually 
                     */
                    if ($_product->getVisibility() != 1) {
                        $productData['id']              = $_product->getSku();
                        $productData['title']           = $_product->getName();
                        $productData['brand']           = $_product->getAttributeText('brand');
                        $productData['description']     = substr(trim(strip_tags($_product->getShortDescription())),0,10000);
                        $productData['product_type']    = $ourCategory;
                        $productData['url']             = Mage::getBaseUrl().$_product->getUrlKey().'.html';
                        $productData['image_link']      = Mage::helper('catalog/image')->init($_product, 'thumbnail')->resize(350,350);//?trim(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . 'catalog/product' . $_chProd
                        $productData['condition']       = 'new';

                        if ($_product->getSpecialPrice()) {
                            $tempPrice = $_product->getSpecialPrice() . ' GBP';
                        } else {
                            $tempPrice = $_product->getPrice() . ' GBP';
                        }

                        // check to see if a tax class is VAT Zero
                        if($_product['tax_class_id'] == 5) {
                            $productData['price'] = $tempPrice;
                        } else {
                            $productData['price'] = round($tempPrice*1.20, 2);
                        }

                        $sql            = "SELECT is_in_stock FROM cataloginventory_stock_item WHERE product_id = '" . $_product->getId() . "'";
                        $resource       = Mage::getSingleton('core/resource');
                        $readConnection = $resource->getConnection('core_read');
                        $row            = $readConnection->fetchAll($sql);

                        if ($row[0]['is_in_stock'] == 0) {
                            $stockLevel = 'out of stock';
                        } else {
                            $stockLevel = 'in stock';
                        }

                        $productData['availability'] = $stockLevel;
                        $productData['shipping']	 ='GB:::0.00 GBP';
                        $productData['mpn']          = $_product->getSku();
                        $productData['gender']       = $_product->getAttributeText('gender');
                        $productData['size']         = $_product->getAttributeText('size');

                        addProductToCsvFile($productData, $handle);
                        $prodCount++;
                    }
            }
	}

	print "\r\n".$prodCount."\r\n";	
	//echo count($_products);
} else {
	echo "CLI Only script.";
}
