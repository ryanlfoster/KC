<?php
/**
 * Space48_FeeshippingPromo
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0), a
 * copy of which is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Space48
 * @package    Space48_FeeshippingPromo
 * @author     Space48 <hello@space48.com>
 * @copyright  Copyright (c) 2012 Space 48 LTD.
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

class Space48_FreeshippingPromo_Model_Freeshippingpromo
{
	public $upperLimit = '';
	public $lowerLimit = '';
	
	public function __construct()
	{
		$this->upperLimit = Mage::getStoreConfig('freeshippingpromo_settings/freeshippingpromo_group/freeshippingpromo_upperlimit',Mage::app()->getStore()->getId());
		$this->lowerLimit = Mage::getStoreConfig('freeshippingpromo_settings/freeshippingpromo_group/freeshippingpromo_lowerlimit',Mage::app()->getStore()->getId());
	}
	
	private function _getCartValue()
	{
		$totals = Mage::getSingleton('checkout/cart')->getQuote()->getTotals();
		return($totals["grand_total"]->getValue());
	}
	
	private function _validDates($dates)
	{
		$_today = date("Y-m-d",Mage::getModel('core/date')->timestamp(time()));
		$dates['from'] = (strlen($dates['from'])?$dates['from']:date("Y-m-d",strtotime("-1 week")));
		$dates['to'] = (strlen($dates['to'])?$dates['to']:date("Y-m-d",strtotime("1 week")));
		if( $dates['from'] < $_today && $_today<=$dates['to'])
		{
			return(true);
		}
	}
	
	private function _checkFreeshippingRules()
	{
		$rulesCollection = Mage::getModel('salesrule/rule')->getCollection();
		$cart = Mage::getSingleton('checkout/cart');
			foreach($rulesCollection as $rule)
			{	
				if($rule->getIsActive() && $rule->getSimpleFreeShipping()>0 && $this->_validDates(array('from'=>$rule->getFromDate(),'to'=>$rule->getToDate())))
				{
					if( $rule->getConditions()->validate(Mage::getSingleton('checkout/cart')) && !strlen($rule->getCode()))
					{
						return(true);
					}
				}
			}
	}
	
	public function getLimits()
	{
		if(!$this->_checkFreeshippingRules())
		{
			return(array('upperLimit' => $this->upperLimit, 'lowerLimit' => $this->lowerLimit, 'cartTotal' => $this->_getCartValue()));
		}
	}
}
