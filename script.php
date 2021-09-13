<?php

use Paysera\CommissionTask\Calculator;

require 'vendor/autoload.php';


$input      = $argv[1];
$calculator = new Calculator();
$calculator->init($input);

