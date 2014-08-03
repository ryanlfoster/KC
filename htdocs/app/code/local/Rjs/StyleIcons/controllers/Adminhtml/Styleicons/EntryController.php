<?php


class Rjs_StyleIcons_Adminhtml_Styleicons_EntryController extends Mage_Adminhtml_Controller_Action
{
    public function _construct() {
        parent::_construct();
    }

    /**
     * Init actions
     *
     * @return Cti_BatchImport_BatchImportController
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
        ->_setActiveMenu('styleicons')
        ->_addBreadcrumb(Mage::helper('styleicons')->__('System'), Mage::helper('styleicons')->__('System'))
        ->_addBreadcrumb(Mage::helper('styleicons')->__('Status'), Mage::helper('styleicons')->__('Status'));
        return $this;
    }

    /**
     * Forward to the manageAction
     */
    public function indexAction() {
        // Set the page title
        $this->_title($this->__('Style Icons'));
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Create new Style Icon
     *
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit Style Icon
     *
     */
    public function editAction()
    {
        $model = Mage::getModel('styleicons/entry');
        if ($id = $this->getRequest()->getParam('id')) {
            $model->load($id);
        }

        Mage::register('_current_entry', $model);

        $this->loadLayout();

        if ($model->getId()) {
            $breadcrumbTitle = Mage::helper('styleicons')->__('Edit Icon');
            $breadcrumbLabel = $breadcrumbTitle;
        }
        else {
            $breadcrumbTitle = Mage::helper('styleicons')->__('New Icon');
            $breadcrumbLabel = Mage::helper('styleicons')->__('Add New Style Icon ');
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Icon'));

        $this->_addBreadcrumb($breadcrumbLabel, $breadcrumbTitle);

        if ($editBlock = $this->getLayout()->getBlock('icon_edit')) {
            $editBlock->setEditMode($model->getId() > 0);
        }

        $this->renderLayout();
    }

    /**
     * Save Icon
     *
     */
    public function saveAction ()
    {
        $request = $this->getRequest();
        if(!$request->isPost()) {
            $this->getResponse()->setRedirect($this->getUrl('*/*'));
        }

        $entry = Mage::getModel('styleicons/entry');

        if($id = (int) $request->getParam('id')) { $entry->load($id); $editing = true; }

        try {
            $entry
            ->setName($request->getParam('name'))
            ->setCompetition($request->getParam('competition'))
            ->setDescription($request->getParam('description'));

            if(!$editing) { $entry->setAdded(Mage::getSingleton('core/date')->gmtDate()); }

            if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
               try {    
                $time = time();
                $uploader = new Varien_File_Uploader('image');
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $media_path = Mage::getBaseDir('media') . DS;     
                $name = $_FILES['image']['name'];
                $bits = explode('.',$name);
                $bits[0] = $bits[0].$time;
                $name = implode('.',$bits);          
                $uploader->save($media_path, $name);
                $data['image'] = $media_path . $name;
            } catch (Exception $e) {
                $this->_getSession()->addException($e, Mage::helper('adminhtml')->__('An error occurred while uploading image.'));
            }                            
        }

        if(isset($data['image'])) { 
            $entry->setImage($data['image']); 
        }

        $entry->save();
        $this->_redirect('*/*');

    } catch (Exception $e) {

        $this->_getSession()->addException($e, Mage::helper('adminhtml')->__($e->getMessage()));
    }

    $this->_forward('new');
}

    /**
     * Delete entry
     *
     */
    public function deleteAction ()
    {
        $entry = Mage::getModel('styleicons/entry')->load($this->getRequest()->getParam('id'));
        if ($entry->getId()) {
            try {
                $entry->delete();
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addException($e, Mage::helper('adminhtml')->__('An error occurred while deleting this entry.'));
            }
        }
        $this->_redirect('*/*');
    }

}