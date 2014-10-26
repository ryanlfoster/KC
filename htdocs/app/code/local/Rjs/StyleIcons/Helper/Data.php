<?php
class Rjs_StyleIcons_Helper_Data extends Mage_Core_Helper_Abstract
{

	public function getCompetition() {

		$_now = date('Ymd');

		$comp = Mage::getModel('styleicons/competition')
		->getCollection()
		->addFieldToFilter('active',1)
		->getFirstItem();

		$start = str_replace('-','',$comp->getStartDate());
		$end   = str_replace('-','',$comp->getEndDate());

		if($comp->getId()) {
			if($_now >= $end) {

				$winner = $this->pickWinner($comp->getId());

				if($winner) {
					Mage::getModel('styleicons/winner')
					->setCompetition($comp->getId())
					->setEntry($winner)
					->save();
				}

				$comp->setActive(0)->save();
				return $this->getCompetition();
			}
			if($_now >= $start) {
				return $comp;
			} else {
				return Mage::getModel('styleicons/competition');
			}
		}
		return $comp;
	}

	/**
	 * [pickWinner description]
	 * @param  [type] $comp_id [description]
	 * @return [type]          [description]
	 */
	public function pickWinner($comp_id) {

		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		
		if($comp_id) {
			$query = 'SELECT entry,COUNT(*) as votes FROM '.$resource->getTableName('styleicons/vote').' 
			WHERE entry IN (SELECT `id` FROM '.$resource->getTableName('styleicons/entry').' WHERE `competition` = '.$comp_id.')
			GROUP BY entry 
			ORDER BY votes DESC
			LIMIT 1;';
		}

		$result = $readConnection->fetchRow($query);

		if($result['votes'] > 0) {
			return $result['entry'];
		} else {
			return false;
		}
	}

	public function getEntries($comp = null) {
		$entries = Mage::getModel('styleicons/entry')->getCollection();

		if($comp == null) {
			$entries->addFieldToFilter('competition',$this->getCompetition()->getId());
		} else {
			$entries->addFieldToFilter('competition',$comp);
		}

		return $entries;
	}

	public function getArchived() {
		return Mage::getModel('styleicons/competition')->getCollection()->addFieldToFilter('active',0);
	}
}
