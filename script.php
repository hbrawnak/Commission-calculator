<?php

use Paysera\CommissionTask\Calculator;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

if (count($argv) < 2) {
    exit("File is required" . PHP_EOL);
}


$input       = $argv[1];
$calculator  = new Calculator();
$commissions = $calculator->init($input);

foreach ($commissions as $commission) {
    echo $commission . PHP_EOL;
}