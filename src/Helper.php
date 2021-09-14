<?php


namespace Paysera\CommissionTask;


class Helper
{
    public static function getPercentage($percentAmount, $amount)
    {
        return round(($percentAmount / 100) * $amount, 2);
    }
}