<?php


namespace Paysera\CommissionTask;


use Paysera\CommissionTask\API\CurrencyExchangeClient;

class Helper
{
    public static function getPercentage($percentAmount, $amount)
    {
        return round(($percentAmount / 100) * $amount, 2);
    }

    public static function getDayDiff($currentDate, $lastDate): float
    {
        $lastDate    = strtotime($lastDate);
        $currentDate = strtotime($currentDate);

        $diff = $currentDate - $lastDate;
        return round($diff / (60 * 60 * 24));
    }

    public static function getCurrencyConversion($fromCurrency, $toCurrency, $amount)
    {
        $exchange = new CurrencyExchangeClient($fromCurrency, $toCurrency, $amount);
        return $exchange->get();
    }
}