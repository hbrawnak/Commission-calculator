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
        if ($this->transaction->getCurrency() == self::CURRENCY_EURO) {
            return $this->euroConversionCommission();
        } else {
            return $this->nonEuroConversionCommission();
        }
    }

    private function nonEuroConversionCommission()
    {
        /* 1 EURO to current currency rate */
        $currentRate = $this->currencyExchange($this->transaction->getCurrency());

        /* Current currency in Euro */
        $totalEuroRate = ($this->transaction->getAmount() / $currentRate);

        /* Current currency conversion */
        $totalCurrentCurrency = $totalEuroRate * $currentRate;

        $user = Cache::get($this->transaction->getId());
        if ($user) {
            $lastDate = $user['date'];

            if (Helper::getDayDiff($this->transaction->getDate(), $lastDate) > $this->weekDay()) {
                /* If last withdraw date is greater than 7 days */

                Cache::delete($this->transaction->getId());

                if ($totalEuroRate > $this->freeWeekAmountLimit()) {
                    $chargeAbleAmount = $this->freeWeekAmountLimit() - $totalEuroRate;
                    $data             = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => 1,
                        'amount' => $this->freeWeekAmountLimit()
                    ];
                    Cache::set($this->transaction->getId(), $data);

                    return Helper::getPercentage($this->privateWithdrawCharge(), $chargeAbleAmount * $currentRate);

                } else {
                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => 1,
                        'amount' => $totalEuroRate
                    ];
                    Cache::set($this->transaction->getId(), $data);

                    return Helper::getPercentage($this->freeOfCharge(), $totalCurrentCurrency);
                }

            } elseif (Helper::getDayDiff($this->transaction->getDate(), $lastDate) >= $this->weekDay()
                && $user['withdrawCount'] <= $this->freeWithdrawLimit()) {
                /* If last withdraw date is less than or equal 7 days and withdraw count less than or equal 3 */

                $chargeAbleAmount = 0;
                if ($user['amount'] >= $this->freeWeekAmountLimit()) {
                    $chargeAbleAmount = $totalCurrentCurrency;

                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => $user['withdrawCount'] + 1,
                        'amount' => $this->freeWeekAmountLimit()
                    ];
                } else {
                    $chargeAbleEuroAmount = $this->freeWeekAmountLimit() - $user['amount'];
                    $chargeAbleAmount     = $chargeAbleEuroAmount * $currentRate;

                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => $user['withdrawCount'] + 1,
                        'amount' => $user['amount'] + $chargeAbleEuroAmount
                    ];
                }


                Cache::set($this->transaction->getId(), $data);
                return Helper::getPercentage($this->privateWithdrawCharge(), $chargeAbleAmount);

            } elseif (Helper::getDayDiff($this->transaction->getDate(), $lastDate) < $this->weekDay() && $user['withdrawCount'] <= $this->freeWithdrawLimit()) {
                /* If last withdraw date is within 7 days and withdraw count less than or equal 3 */

                $chargeAbleAmount = 0;
                if ($user['amount'] >= $this->freeWeekAmountLimit()) {
                    $chargeAbleAmount = $totalCurrentCurrency;

                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => $user['withdrawCount'] + 1,
                        'amount' => $this->freeWeekAmountLimit()
                    ];

                } else {
                    $chargeAbleEuroAmount = $this->freeWeekAmountLimit() - $user['amount'];
                    $chargeAbleAmount     = $chargeAbleEuroAmount * $currentRate;

                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => $user['withdrawCount'] + 1,
                        'amount' => $user['amount'] + $chargeAbleEuroAmount
                    ];
                }

                Cache::set($this->transaction->getId(), $data);
                return Helper::getPercentage($this->privateWithdrawCharge(), $chargeAbleAmount);
            }

        } else {
            /* First 1000 EURO free of charge */

            if ($totalEuroRate > $this->freeWeekAmountLimit()) {
                $chargeAbleEuroAmount = $totalEuroRate - $this->freeWeekAmountLimit();
                $chargeAbleAmount     = $chargeAbleEuroAmount * $currentRate;

                $data = [
                    'date' => $this->transaction->getDate(),
                    'withdrawCount' => 1,
                    'amount' => $this->freeWeekAmountLimit()
                ];
                Cache::set($this->transaction->getId(), $data);

                return Helper::getPercentage($this->privateWithdrawCharge(), $chargeAbleAmount);
            } else {
                $data = [
                    'date' => $this->transaction->getDate(),
                    'withdrawCount' => 1,
                    'amount' => $totalEuroRate
                ];
                Cache::set($this->transaction->getId(), $data);

                return Helper::getPercentage($this->freeOfCharge(), $totalCurrentCurrency);
            }
        }

        /*if ($totalEuroRate <= $this->freeWithdrawLimit()) {
            $data = [
                'date' => $this->transaction->getDate(),
                'withdrawCount' => 1,
                'amount' => $totalEuroRate
            ];
            Cache::set($this->transaction->getId(), $data);

            return Helper::getPercentage($this->privateWithdrawCharge(), $totalCurrentCurrency);

        } else {
            $chargeAbleAmount = $this->freeWithdrawLimit() - $totalEuroRate;
            $data             = [
                'date' => $this->transaction->getDate(),
                'withdrawCount' => 1,
                'amount' => $this->freeWithdrawLimit()
            ];
            Cache::set($this->transaction->getId(), $data);

            return Helper::getPercentage($this->freeOfCharge(), $chargeAbleAmount * $currentRate);
        }*/

    }

    private function euroConversionCommission()
    {
        $user = Cache::get($this->transaction->getId());
        if ($user) {
            $lastDate = $user['date'];

            if (Helper::getDayDiff($this->transaction->getDate(), $lastDate) > $this->weekDay()) {
                /* If last withdraw date is greater than 7 days */

                Cache::delete($this->transaction->getId());

                if ($this->transaction->getAmount() > $this->freeWeekAmountLimit()) {
                    $chargeAbleAmount = $this->freeWeekAmountLimit() - $this->transaction->getAmount();
                    $data             = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => 1,
                        'amount' => $this->freeWeekAmountLimit()
                    ];
                    Cache::set($this->transaction->getId(), $data);

                    return Helper::getPercentage($this->privateWithdrawCharge(), $chargeAbleAmount);

                } else {
                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => 1,
                        'amount' => $this->transaction->getAmount()
                    ];
                    Cache::set($this->transaction->getId(), $data);

                    return Helper::getPercentage($this->freeOfCharge(), $this->transaction->getAmount());
                }


            } elseif (Helper::getDayDiff($this->transaction->getDate(), $lastDate) >= $this->weekDay()
                && $user['withdrawCount'] <= $this->freeWithdrawLimit()) {
                /* If last withdraw date is less than or equal 7 days and withdraw count less than or equal 3 */
                $chargeAbleAmount = 0;
                if ($user['amount'] >= $this->freeWeekAmountLimit()) {
                    $chargeAbleAmount = $this->transaction->getAmount();

                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => $user['withdrawCount'] + 1,
                        'amount' => $this->freeWeekAmountLimit()
                    ];
                } else {
                    $chargeAbleAmount = $this->freeWeekAmountLimit() - $user['amount'];

                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => $user['withdrawCount'] + 1,
                        'amount' => $user['amount'] + $chargeAbleAmount
                    ];
                }

                Cache::set($this->transaction->getId(), $data);
                return Helper::getPercentage($this->privateWithdrawCharge(), $chargeAbleAmount);

            } elseif (Helper::getDayDiff($this->transaction->getDate(), $lastDate) < $this->weekDay() && $user['withdrawCount'] <= $this->freeWithdrawLimit()) {
                /* If last withdraw date is within 7 days and withdraw count less than or equal 3 */

                $chargeAbleAmount = 0;
                if ($user['amount'] >= $this->freeWeekAmountLimit()) {
                    $chargeAbleAmount = $this->transaction->getAmount();

                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => $user['withdrawCount'] + 1,
                        'amount' => $this->freeWeekAmountLimit()
                    ];

                } else {
                    $chargeAbleAmount = $this->freeWeekAmountLimit() - $user['amount'];

                    $data = [
                        'date' => $this->transaction->getDate(),
                        'withdrawCount' => $user['withdrawCount'] + 1,
                        'amount' => $user['amount'] + $chargeAbleAmount
                    ];
                }

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
                    'amount' => $this->freeWeekAmountLimit()
                ];
                Cache::set($this->transaction->getId(), $data);

                return Helper::getPercentage($this->privateWithdrawCharge(), $chargeAbleAmount);
            } else {
                $data = [
                    'date' => $this->transaction->getDate(),
                    'withdrawCount' => 1,
                    'amount' => $this->transaction->getAmount()
                ];
                Cache::set($this->transaction->getId(), $data);

                return Helper::getPercentage($this->freeOfCharge(), $this->transaction->getAmount());
            }
        }


        /*if ($this->transaction->getAmount() <= $this->freeWithdrawLimit()) {
            $data = [
                'date' => $this->transaction->getDate(),
                'withdrawCount' => 1,
                'amount' => $this->transaction->getAmount()
            ];
            Cache::set($this->transaction->getId(), $data);

            return Helper::getPercentage($this->privateWithdrawCharge(), $this->transaction->getAmount());

        } else {
            $chargeAbleAmount = $this->freeWithdrawLimit() - $this->transaction->getAmount();
            $data             = [
                'date' => $this->transaction->getDate(),
                'withdrawCount' => 1,
                'amount' => $this->freeWithdrawLimit()
            ];
            Cache::set($this->transaction->getId(), $data);

            return Helper::getPercentage($this->freeOfCharge(), $chargeAbleAmount);
        }*/

        /*$data = [
            'date' => $this->transaction->getDate(),
            'withdrawCount' => 1,
            'amount' => $this->transaction->getAmount()
        ];
        Cache::set($this->transaction->getId(), $data);

        return Helper::getPercentage($this->privateWithdrawCharge(), $this->transaction->getAmount());*/
    }


    /**
     * @param $amount
     * @param $currency
     * @return StreamInterface
     */
    private function currencyExchange($currency)
    {
        $conversion = Helper::getCurrencyConversion(self::CURRENCY_EURO, $currency, 1);
        return $conversion['result'];
    }

}