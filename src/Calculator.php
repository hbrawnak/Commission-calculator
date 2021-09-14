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
                $model = new Model($item);
                if ($model->getOperationType() == Model::OP_DEPOSIT) {
                    print_r(Helper::getPercentage(0.03, $model->getAmount()) . "\n");
                } else {
                    if ($model->getType() == Model::TYPE_BUSINESS) {
                        print_r(Helper::getPercentage(0.5, $model->getAmount()) . "\n");
                    }

                    if ($model->getType() == Model::TYPE_PRIVATE) {
                        print_r(0 . "\n");
                    }
                }

            }
        }
    }
}