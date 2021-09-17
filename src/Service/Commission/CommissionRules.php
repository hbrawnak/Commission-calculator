<?php


namespace Paysera\CommissionTask\Service\Commission;


class CommissionRules
{
    /**
     * @return float
     */
    public function depositCharge(): float
    {
        return 0.03;
    }

    /**
     * @return float
     */
    public function privateWithdrawCharge(): float
    {
        return 0.3;
    }

    /**
     * @return float
     */
    public function businessWithdrawCharge(): float
    {
        return 0.5;
    }

    /**
     * @return int
     */
    public function freeWeekAmountLimit(): int
    {
        return 1000;
    }


    /**
     * @return int
     */
    public function weekDay()
    {
        return 7;
    }


    /**
     * @return int
     */
    public function freeWithdrawLimit()
    {
        return 3;
    }

}