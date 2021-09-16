<?php


namespace Paysera\CommissionTask;


use Exception;
use Paysera\CommissionTask\Service\Transaction\Commission;
use Paysera\CommissionTask\Service\Reader\CSVReader;

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