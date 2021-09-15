<?php


namespace Paysera\CommissionTask;


use Paysera\CommissionTask\Service\Commission;
use Paysera\CommissionTask\Service\Reader\CSVReader;

class Calculator
{
    public function init($source)
    {
        $reader = new CSVReader($source);
        if ($reader->dataArray()) {
            foreach ($reader->dataArray() as $item) {
                $commission = new Commission(new Transaction($item));
                print_r($commission->process() . PHP_EOL);


                /*$model = new Transaction($item);
                if ($model->getOperationType() == Transaction::OP_DEPOSIT) {
                    print_r(Helper::getPercentage(0.03, $model->getAmount()) . "\n");
                } else {
                    if ($model->getType() == Transaction::TYPE_BUSINESS) {
                        print_r(Helper::getPercentage(0.5, $model->getAmount()) . "\n");
                    }

                    if ($model->getType() == Transaction::TYPE_PRIVATE) {
                        print_r(0 . "\n");
                    }
                }*/

            }
        }
        unset($_SESSION['user']);
    }
}