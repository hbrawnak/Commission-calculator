<?php


namespace Paysera\CommissionTask\Service;


use Paysera\CommissionTask\Helper;
use Paysera\CommissionTask\TransactionInterface;

class Commission
{
    const TYPE_PRIVATE = 'private';
    const TYPE_BUSINESS = 'business';

    const OP_DEPOSIT = 'deposit';
    const OP_WITHDRAW = 'withdraw';

    private $transaction;

    public function __construct(TransactionInterface $transaction)
    {
        $this->transaction = $transaction;
    }


    public function process()
    {
        return $this->processTransactionCommission();
    }

    private function processTransactionCommission()
    {
        if ($this->transaction->getOperationType() == self::OP_DEPOSIT) {
            return $this->depositCommission();
        }

        if ($this->transaction->getOperationType() == self::OP_WITHDRAW) {
            return $this->withdrawCommission();
        }
    }

    private function depositCommission()
    {
        return Helper::getPercentage(0.03, $this->transaction->getAmount());
    }

    private function withdrawCommission()
    {
        if ($this->transaction->getType() == self::TYPE_PRIVATE) {
            return $this->privateAccountCommission();
        }

        if ($this->transaction->getType() == self::TYPE_BUSINESS) {
            return Helper::getPercentage(0.5, $this->transaction->getAmount());
        }
    }


    /*
     * 1000.00 EUR for a week (from Monday to Sunday) is free of charge.
     * Only for the first 3 withdraw operations per a week.
     * 4th and the following operations are calculated by using the rule above (0.3%).
     * If total free of charge amount is exceeded them commission is calculated only for
     * the exceeded amount (i.e. up to 1000.00 EUR no commission fee is applied).
     * */
    private function privateAccountCommission()
    {

        /* First 1000 is free, so set user and date on session */
        $user = Cache::get($this->transaction->getId());
        if ($user) {
            $lastDate = $user['date'];
           if (Helper::getDayDiff($this->transaction->getDate(), $lastDate) > 7) {
               $data = ['date' => $this->transaction->getDate(), 'count' => 1];
               Cache::set($this->transaction->getId(), $data);

               return Helper::getPercentage(0.3, $this->transaction->getAmount());
           } else {
               $data = ['date' => $this->transaction->getDate(), 'count' => $user['user']['count'] + 1];
               Cache::set($this->transaction->getId(), $data);


           }
        } else {
            if ($this->transaction->getAmount() > $this->freeWeekLimit()) {
                $chargeAbleAmount = $this->transaction->getAmount() - $this->freeWeekLimit();

                $data =  ['date' => $this->transaction->getDate(), 'count' => 1];
                Cache::set($this->transaction->getId(), $data);

                return Helper::getPercentage(0.3, $chargeAbleAmount);
            }
        }



        return Helper::getPercentage(0.3, $this->transaction->getAmount());
    }


    private function freeWeekLimit()
    {
        return 1000;
    }


}