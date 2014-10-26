<?php

class Rjs_StyleIcons_IndexController extends Mage_Core_Controller_Front_Action {

	public function IndexAction() {

		$comp 	 = Mage::helper('styleicons')->getCompetition();
		$entries = Mage::helper('styleicons')->getEntries();

		$this->loadLayout();  
		$this->getLayout()->getBlock('styleicons_index_index')->setCompetition($comp)->setEntries($entries);
		$this->_initLayoutMessages('customer/session');
		$this->renderLayout();
	}

	public function VoteAction() {
		$this->_initLayoutMessages('customer/session');
		if($id = (int) $this->getRequest()->getParam('id')) {
			
			try {
				$ip    = (string) $_SERVER['REMOTE_ADDR'];
				$entry  = Mage::getModel('styleicons/entry')->load($id);
				$competition = Mage::getModel('styleicons/competition')->load($entry->getCompetition());
				$check = Mage::getModel('styleicons/vote')->getCollection()->addFieldToFilter('ip',$ip);

				if($competition->getActive()) {
					if(!$check->getSize()) {
						$vote = Mage::getModel('styleicons/vote');
						$vote->setData(array('entry' => $entry->getId(),'ip' => $ip));

						try {
							$vote->save();
							Mage::getSingleton('customer/session')->addSuccess('You vote for '.$entry->getName().' has been recorded');
						} catch (Exception $e) {
							Mage::getSingleton('customer/session')->addError('There was an error with your request');
						}
					} else {
						Mage::getSingleton('customer/session')->addError('Sorry, you have already voted');
					}
				} else {
					Mage::getSingleton('customer/session')->addError('This competition has already closed.');
				}
			} catch (Exception $e) {
				Mage::getSingleton('customer/session')->addError('There was an error with your request');
			}
		}

		$this->_redirect('*');
	}
}