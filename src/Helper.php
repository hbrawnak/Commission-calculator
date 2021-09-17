<?php


namespace App\CommissionTask;


use App\CommissionTask\Service\API\CurrencyExchangeClient;
use Psr\Http\Message\StreamInterface;

class Helper
{
    /**
     * @param $percentAmount
     * @param $amount
     * @return false|float
     */
    public static function getPercentage($percentAmount, $amount)
    {
        return round(($percentAmount / 100) * $amount, 2);
    }

    /**
     * @param $currentDate
     * @param $lastDate
     * @return float
     */
    public static function getDayDiff($currentDate, $lastDate): float
    {
        $lastDate    = strtotime($lastDate);
        $currentDate = strtotime($currentDate);

        $diff = $currentDate - $lastDate;
        return round($diff / (60 * 60 * 24));
    }

    /**
     * @param $fromCurrency
     * @param $toCurrency
     * @param $amount
     * @return StreamInterface
     */
    public static function getCurrencyConversion($fromCurrency, $toCurrency, $amount)
    {
        $exchange = new CurrencyExchangeClient($fromCurrency, $toCurrency, $amount);
        return $exchange->get();
    }
}