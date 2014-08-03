<?php

class Rjs_StyleIcons_Adminhtml_Styleicons_CompetitionController extends Mage_Adminhtml_Controller_Action
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
        ->_setActiveMenu('styleicons');
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
        $model = Mage::getModel('styleicons/competition');

        if ($id = $this->getRequest()->getParam('id')) {
            $model->load($id);
        }

        Mage::register('_current_competition', $model);

        $this->loadLayout();

        if ($model->getId()) {
            $breadcrumbTitle = Mage::helper('styleicons')->__('Edit Competition');
            $breadcrumbLabel = $breadcrumbTitle;
        }
        else {
            $breadcrumbTitle = Mage::helper('styleicons')->__('New Competition');
            $breadcrumbLabel = Mage::helper('styleicons')->__('Add Competition ');
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Competition'));

        $this->_addBreadcrumb($breadcrumbLabel, $breadcrumbTitle);

        if ($editBlock = $this->getLayout()->getBlock('styleicons_competition_edit')) {
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
        $session = Mage::getSingleton('core/session');
        if(!$request->isPost()) {
            $this->getResponse()->setRedirect($this->getUrl('*/*'));
        }

        $competition = Mage::getModel('styleicons/competition');
        $editing = false;
        if($id = (int) $request->getParam('id')) { $competition->load($id); if($competition->getId()) { $editing = true; } }

        $startDate   = $request->getParam('start_date');
        $endDate     = $request->getParam('end_date');
        $currentDate = Mage::getSingleton('core/date')->gmtDate();

        if($endDate < $currentDate) {
            $session->addError(Mage::helper('adminhtml')->__('The end date must be equal or greater than today.'));
            $this->_redirect('*/*');
            return false;
        }

        if($endDate <= $startDate) {
            $session->addError(Mage::helper('adminhtml')->__('The end date must be greater than start date.'));
            $this->_redirect('*/*');
            return false;
        }

        try {
            $competition
            ->setName($request->getParam('name'))
            ->setStartDate($startDate)
            ->setEndDate($endDate);
            if(!$editing) { $competition->setAdded(Mage::getSingleton('core/date')->gmtDate()); }
            if(!$editing) { $competition->setActive(1); }
            $competition->save();
            $this->_redirect('*/*');
        } catch (Exception $e) {
            $this->_getSession()->addException($e, Mage::helper('adminhtml')->__('An error occurred while saving this competition.'));
        }

        $this->_forward('new');
    }

    /**
     * Delete competition
     *
     */
    public function deleteAction ()
    {
        $competition = Mage::getModel('styleicons/competition')
        ->load($this->getRequest()->getParam('id'));
        if ($competition->getId()) {
            try {
                $competition->delete();
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addException($e, Mage::helper('adminhtml')->__('An error occurred while deleting this competition.'));
            }
        }
        $this->_redirect('*/*');
    }

}