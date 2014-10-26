<?php

class Rjs_StyleIcons_ArchiveController extends Mage_Core_Controller_Front_Action {
	
	public function IndexAction() {

		if($id = $this->getRequest()->getParam('id')) {
			$comp = Mage::getModel('styleicons/competition')->load($id);
			if($comp->getActive() == '0') {
				$this->loadLayout();
				$entries = Mage::getModel('styleicons/entry')
				->getCollection()
				->addFieldToFilter('competition',$comp->getId());
				$winner = Mage::getModel('styleicons/winner')
				->getCollection()
				->addFieldToFilter('competition',$comp->getId())
				->getFirstItem();
				$winner = Mage::getModel('styleicons/entry')->load($winner->getEntry());
				$this->getLayout()->getBlock('styleicons_index_index')->setCompetition($comp)->setEntries($entries);
				$this->getLayout()->getBlock('styleicons_index_index')->setCompetition($comp)->setWinner($winner);
				$this->renderLayout(); 
			} else {
				$this->_redirect('*/index');
			}
		} else {

			$this->_redirect('*/index');
		}
		
	}
}