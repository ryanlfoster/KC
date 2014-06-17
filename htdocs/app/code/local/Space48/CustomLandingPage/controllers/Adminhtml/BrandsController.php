<?php
/**
 * Space48 Ltd
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.space48.com/license.html
 *
 * @category   Space48
 * @package    Space48_Custom_Landing_Page
 * @version    0.1.0
 * @copyright  Copyright (c) 2013-2013 Space48 Ltd. (http://www.space48.com)
 * @license    http://www.space48.com/license.html
 * @company    Space48
 * @author     James Cowie (james@space48.com), Matt Edwards (matt@space48.com)
 * @link       http://wiki.space48.com/modules/brands
 */
class Space48_CustomLandingPage_Adminhtml_BrandsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * InitAction.
     *
     * @return Space48_Splash_Adminhtml_BrandsController
     */
    protected function _initAction()
    { 
        $this->loadLayout()
             ->_setActiveMenu('customlandingpage/items')
             ->_addBreadcrumb(Mage::helper('adminhtml')->__('Brands Manager'), Mage::helper('adminhtml')->__('Brands Manager'));

        return $this;
    }

    /**
     * Default action.
     *
     * Sets calls _initAction. loads layout and renders.
     */
    public function indexAction()
    {
        $this->_initAction();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Edit Action
     *
     * Allows for editing or creating a new brand page.
     * @return layout
     */
    public function editAction()
    {
        $brandsId    = $this->getRequest()->getParam('id');
        $brandsModel = Mage::getModel('customlandingpage/brands')->load($brandsId);

        if($brandsModel->getId() || $brandsId == 0) {
            Mage::register('brands_data', $brandsModel);
            $this->loadLayout();
            $this->_setActiveMenu('customlandingpage/items');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Brands Manager'), Mage::helper('adminhtml')->__('Brands Manager'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('customlandingpage/adminhtml_brands_edit'));
            
            $this->_addLeft($this->getLayout()->createBlock('customlandingpage/adminhtml_brands_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brands')->__('Brand does not exist'));
            $this->_redirect('*/*/');
        }
    }

    /**
     * Save action redirects to edit
     *
     * @return redirect
     */
    public function newAction()
    {
        $this->_redirect('*/*/edit');
    }

    /**
     * Save action called when a new or edited page is saved.
     */
    public function saveAction()
    {
        
        Zend_Debug::dump($this->getRequest()->getPost());
        die();
        
        if($this->getRequest()->getPost()) {
            $postData    = $this->getRequest()->getPost();
            $brandsModel = Mage::getModel('customlandingpage/brands');
            
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

                        $path = Mage::getBaseDir('media') . DS  . 'brands';
                        $uploader->save($path, $_FILES['large_logo']['name']);

                        $brandsModel->setLargeLogo($_FILES['large_logo']['name']);
                    } catch(Exception $e) {
                        Mage::throwException(Mage::helper('customlandingpage')->__('Error saving the image' . $e));
                    }
                }

                $brandsModel->save();

                if(!$brandsModel->getId()) {
                    Mage::throwException(Mage::helper('customlandingpage')->__('Error saving attribute'));
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

    /**
     * Delete action,
     * Allows for deleting a brand page from the system,
     *
     * @return bool
     */
    public function deleteAction()
    {
        // TODO implement the delete action here.
    }


}