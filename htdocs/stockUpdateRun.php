<?php
    $mage = dirname(dirname(__file__)) . "/htdocs/app/Mage.php";
    require_once($mage);
    
    Mage::app();
    
    $stockUpdate = Mage::getModel('kidscavern_stockupdate/updater');
    $stockUpdate->run();

?>