<?php

use Paysera\CommissionTask\Calculator;

require 'vendor/autoload.php';

if (count($argv) < 2) {
    exit("File is required" . PHP_EOL);
}

session_start();

$input      = $argv[1];
$calculator = new Calculator();
$calculator->init($input);

