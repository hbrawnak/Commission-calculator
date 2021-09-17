<?php


namespace App\CommissionTask;


use Exception;
use App\CommissionTask\Service\Commission\Commission;
use App\CommissionTask\Service\Reader\CSVReader;
use App\CommissionTask\Service\Transaction\Transaction;

class Calculator
{
    /**
     * @param $source
     * @return array
     * @throws Exception
     */
    public function init($source)
    {
        $commissionArray = [];
        $reader          = new CSVReader($source);
        if ($reader->dataArray()) {
            foreach ($reader->dataArray() as $item) {
                $commission        = new Commission(new Transaction($item));
                $commissionArray[] = $commission->process();
            }
        }

        return $commissionArray;
    }
}