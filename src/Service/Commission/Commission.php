<?php


namespace Paysera\CommissionTask\Service\Commission;


use Paysera\CommissionTask\Helper;
use Paysera\CommissionTask\Service\Cache;
use Paysera\CommissionTask\Service\Transaction\TransactionInterface;
use Psr\Http\Message\StreamInterface;

class Commission extends CommissionRules implements CommissionInterface
{
    const TYPE_PRIVATE  = 'private';
    const TYPE_BUSINESS = 'business';

    const OP_DEPOSIT  = 'deposit';
    const OP_WITHDRAW = 'withdraw';

    const CURRENCY_EURO = 'EUR';

    private $transaction;

    /**
     * Commission constructor.
     * @param TransactionInterface $transaction
     */
    public function __construct(TransactionInterface $transaction)
    {
        $this->transaction = $transaction;
    }


    /**
     * @return false|float
     */
    public function process()
    {
        return $this->processTransactionCommission();
    }

    /**
     * @return false|float
     */
    private function processTransactionCommission()
    {
        if ($this->transaction->getOperationType() == self::OP_DEPOSIT) {
            return $this->depositCommission();
        }

        if ($this->transaction->getOperationType() == self::OP_WITHDRAW) {
            return $this->withdrawCommission();
        }
    }

    /**
     * @return false|float
     */
    private function depositCommission()
    {
        return Helper::getPercentage($this->depositCharge(), $this->transaction->getAmount());
    }

    /**
     * @return false|float
     */
    private function withdrawCommission()
    {
        if ($this->transaction->getType() == self::TYPE_PRIVATE) {
            return $this->privateAccountCommission();
        }

        if ($this->transaction->getType() == self::TYPE_BUSINESS) {
            return $this->businessAccountCommission();
        }
    }

    /**
     * @return false|float
     */
    private function businessAccountCommission()
    {
        return Helper::getPercentage($this->businessWithdrawCharge(), $this->transaction->getAmount());
    }


    /**
     * @return false|float
     */
    private function privateAccountCommission()
    {

        /* First 1000 is free, so set user and date on session */
        //$this->currencyExchange($this->transaction->getAmount(), $this->transaction->getCurrency());
        $user = Cache::get($this->transaction->getId());
        if ($user) {
            $lastDate = $user['date'];
            /* If last withdraw date is bigger than 7 days */
            if (Helper::getDayDiff($this->transaction->getDate(), $lastDate) > $this->weekDay()) {

                $data = [
                    'date' => $this->transaction->getDate(),
                    'withdrawCount' => 1,
                    'amount' => $this->transaction->getAmount()
                ];
                Cache::set($this->transaction->getId(), $data);

                return Helper::getPercentage($this->privateWithdrawCharge(), $this->transaction->getAmount());

            } elseif (Helper::getDayDiff($this->transaction->getDate(), $lastDate) >= $this->weekDay()
                && $user['withdrawCount'] < $this->freeWithdrawLimit()) {
                /* If last withdraw date is smaller or equal than 7 days and withdraw count less than 3 */
                $chargeAbleAmount = 0;
                if ($user['amount'] > $this->freeWeekAmountLimit()) {
                    $chargeAbleAmount = $this->transaction->getAmount();
                } else {
                    $chargeAbleAmount = $this->freeWeekAmountLimit() - $user['amount'];
                }

                $data = [
                    'date' => $this->transaction->getDate(),
                    'withdrawCount' => $user['withdrawCount'] + 1,
                    'amount' => $user['amount'] + $chargeAbleAmount
                ];
                Cache::set($this->transaction->getId(), $data);
                return Helper::getPercentage($this->privateWithdrawCharge(), $chargeAbleAmount);
            }
        } else {
            /* First 1000 EURO free of charge */
            if ($this->transaction->getAmount() > $this->freeWeekAmountLimit()) {
                $chargeAbleAmount = $this->transaction->getAmount() - $this->freeWeekAmountLimit();

                $data = [
                    'date' => $this->transaction->getDate(),
                    'withdrawCount' => 1,
                    'amount' => $this->transaction->getAmount()
                ];
                Cache::set($this->transaction->getId(), $data);

                return Helper::getPercentage($this->privateWithdrawCharge(), $chargeAbleAmount);
            }
        }

        $data = [
            'date' => $this->transaction->getDate(),
            'withdrawCount' => 1,
            'amount' => $this->transaction->getAmount()
        ];
        Cache::set($this->transaction->getId(), $data);

        return Helper::getPercentage($this->privateWithdrawCharge(), $this->transaction->getAmount());
    }


    /**
     * @param $amount
     * @param $currency
     * @return StreamInterface
     */
    private function currencyExchange($amount, $currency)
    {
        //TODO Fix the API issue ASAP

        if ($currency !== self::CURRENCY_EURO) {
            $conversion = Helper::getCurrencyConversion($currency, self::CURRENCY_EURO, $amount);
            return $conversion;
        } else {
            return $amount;
        }
    }

}