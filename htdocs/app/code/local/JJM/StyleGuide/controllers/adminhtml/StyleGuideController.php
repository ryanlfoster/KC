<?php

class JJM_Styleguide_Adminhtml_StyleguideController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("styleguide/styleguide")->_addBreadcrumb(Mage::helper("adminhtml")->__("Styleguide  Manager"),Mage::helper("adminhtml")->__("Styleguide Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Styleguide"));
			    $this->_title($this->__("Manager Styleguide"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Styleguide"));
				$this->_title($this->__("Styleguide"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("styleguide/styleguide")->load($id);

				if ($model->getId()) {
					Mage::register("styleguide_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("styleguide/styleguide");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Styleguide Manager"), Mage::helper("adminhtml")->__("Styleguide Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Styleguide Description"), Mage::helper("adminhtml")->__("Styleguide Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("styleguide/adminhtml_styleguide_edit"))->_addLeft($this->getLayout()->createBlock("styleguide/adminhtml_styleguide_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("styleguide")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Styleguide"));
		$this->_title($this->__("Styleguide"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("styleguide/styleguide")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("styleguide_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("styleguide/styleguide");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Styleguide Manager"), Mage::helper("adminhtml")->__("Styleguide Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Styleguide Description"), Mage::helper("adminhtml")->__("Styleguide Description"));


		$this->_addContent($this->getLayout()->createBlock("styleguide/adminhtml_styleguide_edit"))->_addLeft($this->getLayout()->createBlock("styleguide/adminhtml_styleguide_edit_tabs"));

		$this->renderLayout();

		}

		public function saveAction() {

			$post_data = $this->getRequest()->getParams();
            $post_images = $_FILES;


			if ($post_data) {

				try {

						
				    //save image
		            try {


                        if (isset($_FILES)) {

                            foreach($_FILES as $id => $file) {

                                unset($post_data[$id]);

                                if ($file['name']) {
                                    if($this->getRequest()->getParam("id")){
                                        $model = Mage::getModel("styleguide/styleguide")->load($this->getRequest()->getParam("id"));
                                        if($model->getData($id)){
                                                $io = new Varien_Io_File();
                                                $io->rm(Mage::getBaseDir('media').DS.implode(DS,explode('/',$model->getData($id))));
                                        }
                                    }

                                    $path = Mage::getBaseDir('media') . DS . 'styleguide' . DS .'styleguide'.DS;
                                    $uploader = new Varien_File_Uploader($id);
                                    $uploader->setAllowedExtensions(array('jpg','png','gif'));
                                    $uploader->setAllowRenameFiles(false);
                                    $uploader->setFilesDispersion(false);
                                    $destFile = $path.$file['name'];
                                    $filename = str_replace('&','', $uploader->getNewFileName($destFile));
                                    //var_dump($filename);die;
                                    $uploader->save($path, $filename);
                                    $post_data[$id]='styleguide/styleguide/'.$filename;
                                }
                            }
                        }

                    } catch (Exception $e) {
                            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                            return;
                    }
                    //save image


                    $model = Mage::getModel("styleguide/styleguide")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"))
                    ->save();

                    $current = Mage::getModel('styleguide/products')->getCollection()->addFieldToFilter('styleguide_id',$model->getId());
                    foreach($current as $obj) {
                        $obj->delete();
                    }

                    if(array_key_exists('pids',$post_data)) {
                        foreach($post_data['pids'] as $product_id) {
                            Mage::getModel('styleguide/products')->setStyleguideId($model->getId())->setProductId($product_id)->save();
                        }
                    }

                    Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Styleguide was successfully saved"));
                    Mage::getSingleton("adminhtml/session")->setStyleguideData(false);

                    if ($this->getRequest()->getParam("back")) {
                        $this->_redirect("*/*/edit", array("id" => $model->getId()));
                        return;
                    }
                    $this->_redirect("*/*/");
                    return;
                } catch (Exception $e) {
                    Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                    Mage::getSingleton("adminhtml/session")->setStyleguideData($this->getRequest()->getPost());
                    $this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
                    return;
                }

			}

			$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("styleguide/styleguide");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('styleguide_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("styleguide/styleguide");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
}
