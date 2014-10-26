<?php

/**
 * Checkout events observer
 *
 * @category   Ebizmarts
 * @package    Ebizmarts_SagePaySuite
 * @author     Ebizmarts <info@ebizmarts.com>
 */

class Ebizmarts_SagePaySuite_Model_Observer_Checkout extends Ebizmarts_SagePaySuite_Model_Observer
{

	protected function _getLastOrderId()
	{
		return (int)(Mage::getSingleton('checkout/type_onepage')->getCheckout()->getLastOrderId());
	}

	/**
	 * Clear SagePaySuite session when loading onepage checkout
	 */
	public function controllerOnePageClear($o)
	{
		$this->getSession()->clear();
	}

	public function controllerMultishippingClear($o)
	{
		$this->getSession()->clear();
	}

	public function controllerOnePageSuccess($o)
	{

		//Capture data from Sage Pay API
		$orderId = $this->_getLastOrderId();

		$this->_getTransactionsModel()->addApiDetails($orderId);


		/**
		 * Delete session tokencards if any
		 */
        $vdata = Mage::getSingleton('core/session')->getVisitorData();

        $sessionCards = Mage::getModel('sagepaysuite2/sagepaysuite_tokencard')->getCollection()
                ->addFieldToFilter('visitor_session_id', (string)$vdata['session_id']);

        if($sessionCards->getSize() > 0){
            foreach($sessionCards as $_c){
                if($_c->getCustomerId() == 0){
                    $_c->delete();
                }
            }
        }
		/**
		 * Delete session tokencards if any
		 */
		$this->getSession()->clear();
	}

}