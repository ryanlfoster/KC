<?php

ini_set("memory_limit", "512M");
ini_set("max_execution_time", "0");
set_time_limit(0);

if (PHP_SAPI == "cli") {
    $opts = getopt("m:f:vh");
}
else {
    ini_set("implicit_flush", true);
    
    echo "<pre>\n";
    $opts = $_GET;
    if ($opts['key'] != md5($_SERVER['HTTP_HOST'])) {
        
        // generate access key with:
        // echo -n [SERVER_HOST] | md5sum -
        exit("Invalid web access key (" . $_SERVER['HTTP_HOST'] . ")\n");
    }
}

if (isset($opts["h"]) || !isset($opts["m"]) || !isset($opts["f"])) {
    usage();
    exit;
}

$modelPath = $opts["m"];
$function = $opts["f"];

define("DEBUG_MODE", isset($opts["v"]) ? true : false);

require 'app/Mage.php';

if (!Mage::isInstalled()) {
    echo "Application is not installed yet, please complete install wizard first.";
    exit;
}

Mage::app('admin')->setUseSessionInUrl(false);
Mage::getConfig()->init();

$model = Mage::getModel($modelPath);
if (!$model) {
    echo "Model " . $modelPath . " doesn't exist.\n";
    exit;
}

if (!method_exists($model, $function)) {
    echo "Method " . $function . " on class " . get_class($model) . " doesn't exist.\n";
    exit;
}

//echo "Calling user func " . $function . " on " . get_class($model) . "\n";

$output = $model->$function();

if ($output != "") {
    echo "Output produced:\n\n";
    print_r($output);
}
//call_user_func(array($model, $function));

function usage() {
    global $argv;
    echo "Usage:\n";
    echo "php " . $argv[0] . " [options]\n";
    echo "-m model/path\tMagento Model Path\n";
    echo "-f function\tFunction in class\n";
    echo "-v\t\tBe verbose, log to STDOUT as opposed to log file\n";
    echo "-h\t\tThis menu\n";
}
