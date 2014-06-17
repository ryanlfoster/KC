<?php
class Space48_Brands_Adminhtml_BrandController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout();

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction();
        $this->renderLayout();
    }

    public function editAction()
    {
        $brandsId    = $this->getRequest()->getParam('id');
        $brandsModel = Mage::getModel('brands/brands')->load($brandsId);

        if($brandsModel->getId() || $brandsId == 0) {
            Mage::register('brands_data', $brandsModel);

            $this->loadLayout();
            $this->_addContent($this->getLayout()->createBlock('brands/adminhtml_brands_edit'))
                ->_addLeft($this->getLayout()->createBlock('brands/adminhtml_brands_edit_tabs'));
            $this->renderLayout();
        }
    }

    public function saveAction()
    {
        $id      = $this->getRequest()->getParam('id');
        $brandId = Mage::helper('brands/brands')->getManufacturerIdByBrandId($id);

        if($this->getRequest()->getPost()) {
            $postData    = $this->getRequest()->getPost();
            $brandsModel = Mage::getModel('brands/brands');
            $id          = $this->getRequest()->getParam('id');

            if($id) {
                $brandsModel->load($id);
            }

            unset($postData['large_logo']);
            $brandsModel->setData($postData);
            Mage::getSingleton('adminhtml/session')->setFormData($postData);

            try{
                if($id) {
                    $brandsModel->setId($id);
                }

                if($_FILES['large_logo']['size'] > 0) {
                    try {
                        $uploader = new Varien_File_Uploader('large_logo');
                        $uploader->setAllowedExtensions(array('jpg','png','jpeg','gif'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);

                        $path = Mage::getBaseDir('media') . DS  . 'aitmanufacturers';
                        $uploader->save($path, $_FILES['large_logo']['name']);

                        $brandsModel->setLargeLogo($_FILES['large_logo']['name']);
                    } catch(Exception $e) {
                        Mage::throwException(Mage::helper('brands')->__('Error saving the image' . $e));
                    }
                }

                $brandsModel->save();

                // need to save the positions of the products.
                $productIds = $this->getRequest()->getParam('product_id');
                $positions  = $this->getRequest()->getParam('position');

                $newPositions = array_combine($productIds, $positions);

                $products = Mage::getModel('products/products')->getCollection()
                    ->addFieldToFilter('manufacturer_id', $brandId)
                    ->addFieldToFilter('product_id', array($productIds));

                $productModel = Mage::getSingleton('products/products');

                foreach ($products as $product) {
                    $tmp = $productModel->load($product->getId());
                    $tmp->setPosition($newPositions[$product->getProductId()]);
                    $tmp->save();
                }

                if(!$brandsModel->getId()) {
                    Mage::throwException(Mage::helper('brands')->__('Error saving attribute'));
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Brand was saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/');

                return;

            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setModuleData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_redirect('*/*/edit');
    }


}