<?php


namespace Paysera\CommissionTask;


use Paysera\CommissionTask\Service\Reader\CSVReader;

class Calculator
{
    public function init($source)
    {
        $reader = new CSVReader($source);
        if ($reader->dataArray()) {
            foreach ($reader->dataArray() as $item) {
                print_r($item);
            }
        }
    }
}