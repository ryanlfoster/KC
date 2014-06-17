<?php
exit("DO NOT RUN!");

//$_SERVER['HTTP_HOST'] = "djones.kc2.dev.rippleffect.com";
$_SERVER['HTTP_HOST'] = "www.kidscavern.co.uk";

include '../app/Mage.php';
Mage::app('admin')->setUseSessionInUrl(false);
Mage::getConfig()->init();

$csvPath = dirname(dirname(__FILE__)) . "/var/import/rs_user.csv";
$fh = fopen($csvPath, "r");
$rowcount = 0; $columns = array();

$regions = Mage::getResourceModel('directory/region_collection');
$store = Mage::getModel('core/store')->load(1);

while ($row = fgetcsv($fh)) {
    $rowcount++;
    unset($regionId);
    
    if ($rowcount == 1) {
        // file headers
        $columns = $row;
        continue;
    }
    
    $row = _mapDataToArray($columns, $row);
    
    echo "Importing: " . $row['fname'] . " " . $row['lname'] . " (" . $row['email'] . ")\n";
    
    $customer = Mage::getModel('customer/customer');
    /* @var $customer Mage_Customer_Model_Customer */
    
    $password = Mage::helper('core')->getRandomString(8, Mage_Core_Helper_Data::CHARS_PASSWORD_LOWERS . Mage_Core_Helper_Data::CHARS_PASSWORD_DIGITS);
    
    $customer->setStore($store);
    
    $customer->setFirstname(ucwords($row['fname']));
    $customer->setLastname(ucwords($row['lname']));
    $customer->setEmail(strtolower($row['email']));
    //$customer->setEmail("dan@rippleffect.com");
    
    $customer->setPassword($password);
    
    if ($row['gender'] == "M") {
        $customer->setGender("male");
    }
    else if ($row['gender'] == "F") {
        $customer->setGender("female");
    }
    
    try {
        echo "\tSaving..\n";
        $customer->save();
        $customer->setConfirmation(null);
        $customer->save(); 
        $customer->sendNewAccountEmail('registered', Mage::getUrl('customer/account/login'), 1);
    }
    catch (Exception $e) {
        echo "\tError: couldn't save customer: " . $e->getMessage() . "\n";
        continue;
    }
    
    if ($row['mailing_list'] == 1) {
        echo "\tSubscribing to newsletter list..\n";
        
        $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($customer->getEmail());
        /* @var $subscriber Mage_Newsletter_Model_Subscriber */
        
        if (!$subscriber->getId()
                || $subscriber->getStatus() == Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED
                || $subscriber->getStatus() == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE)
        {

            $subscriber->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
            $subscriber->setSubscriberEmail($customer->getEmail());
            $subscriber->setSubscriberConfirmCode($subscriber->RandomSequence());
        }

        $subscriber->setStoreId($store->getId());
        $subscriber->setCustomerId($customer->getId());

        try {
            echo "\tSaving..\n";
            $subscriber->save();
        }
        catch (Exception $e) {
            echo "\tError: couldn't subscribe customer: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\tAdding address..\n";
    $address = Mage::getModel('customer/address');
    /* @var $address Mage_Customer_Model_Address */
    
    $address->setCustomer($customer);
    
    $address->setFirstname($customer->getFirstname());
    $address->setLastname($customer->getLastname());
    $address->setStreet(array(ucwords($row['address1']), ucwords($row['address2'])));
    $address->setCity(ucwords($row['city']));
    
    if ($row['province'] != "") {
        $regions->addRegionNameFilter($row['province'])->load();
        if ($regions) foreach($regions as $region) {
            $regionId = intval($region->getId());
        }

        $address->setRegion(ucwords($row['province']));
        if (isset($regionId)) {
            $address->setRegionId($regionId);
        }
    }
    
    $address->setPostcode(strtoupper($row['postcode']));
    $address->setCountryId($row['country_2_code']);
    
    $address->setTelephone($row['telephone']);
    
    $address->setIsDefaultBilling(true);
    $address->setIsDefaultShipping(true);
    
    echo "\tSaving..\n";
    $address->save();
    
    echo "\tDone\n";
}



function _mapDataToArray($columnsArray, $dataArray) {
    $finalArray = array();
    foreach ($columnsArray as $i => $key) {
        $finalArray[$key] = $dataArray[$i];
    }
    return $finalArray;
}
