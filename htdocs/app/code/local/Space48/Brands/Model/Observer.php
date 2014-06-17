<?php

class Space48_Brands_Model_Observer
{
    /**
     * Update the Space 48 brands table based on selection..
     *
     * @param Varien_Event_Observer $observer
     */
    public function updateAttributeTable(Varien_Event_Observer $observer)
    {
        $productId = $observer->getProduct()->getData('entity_id');

        $model = Mage::getModel('products/products')->getCollection();
        $model->addFieldToFilter('product_id', array('eq' => $productId));

        $brand = $observer->getProduct()->getData('brand');

        if (!$model->getData()) {
            // we have no results so we need to insert into the table.
            try {
                $brandsModel = Mage::getModel('products/products');
                $brandsModel->setData('product_id', $productId);
                $brandsModel->setData('manufacturer_id', $brand);
                $brandsModel->setData('position', '999999');

                $brandsModel->save();
            } catch (Exception $e) {
                Mage::throwException($e);
            }
        }
    }
}