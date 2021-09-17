<?php

use App\CommissionTask\Calculator;
use App\CommissionTask\Service\Cache\Cache;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

if (count($argv) < 2) {
    exit("File is required" . PHP_EOL);
}


$input       = $argv[1];

$cache = Cache::getInstance();

$calculator  = new Calculator();
$commissions = $calculator->init($input);

foreach ($commissions as $commission) {
    echo $commission . PHP_EOL;
}

$cache->destroy();