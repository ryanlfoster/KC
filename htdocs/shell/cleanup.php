<?php
/*
* Grab the Magento config so that we can use core Magento functions
*
* Currently just cleans the sales_flat_quote* tables.
*/ 
$approot = dirname(dirname(__file__));
require_once( $approot.'/app/Mage.php' );
Mage::app('default'); 

$tables = array(
	         array('table' => 'sales_flat_quote',	
                       'where' => 'created_at'),
	         array('table' => 'sales_flat_quote_item',
		       'where' => 'created_at'),
	         array('table' => 'sales_flat_quote_item_option',
		       'where' => 'false'),
);

if(isset($_SERVER['SHELL']) && !empty($_SERVER['SHELL'])) {
    // Call the clean tables function. Could be passed as arg call from the command line.
    cleanTables('7', $tables);
} else {
    echo "This is a CLI only script";
}
/**
* Function to clean tables based on type of table. 
* @param number of days to subtract from today for the delete from query.
*/
function cleanTables($days, $tables)
{
    $db = Mage::getSingleton('core/resource')->getConnection();
    // create a nested array as some tables have different where conditions.
 
    // create a new date object and subtract the number of days based on param passed in.
    $date    = date('Y-m-j G:i:s');
    $oldDate = date('Y-m-j G:i:s', strtotime('-' . $days . ' day' . $date));

    foreach($tables as $table) {
        if($table['where'] != 'false') {
	    $db->query("DELETE FROM " . $table['table'] . " WHERE " . $table['where'] . " <= '" . $oldDate . "' ");	
	    Mage::log('Completed cleanup for table: ' . $table['table'] . ' Where data was created before: ' . $oldDate, null, 'cleanup.log');
	} else {
	    // delete only the options that dont have a parent in the item table
	    // this option is not required for kids cavern as the tables are InnoDB and delete down.
	    //$db->query("DELETE FROM " . $table['table'] . " WHERE 'item_id' NOT IN (SELECT item_id FROM sales_flat_quote_item)");
	    //Mage::log('Completed cleanup for table: ' . $table['table'] . ' This data was truncated as there is no start date.' , null, 'cleanup.log');
	}
    }
}
