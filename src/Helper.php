<?php


namespace Paysera\CommissionTask;


class Helper
{
    public static function getPercentage($percentAmount, $amount)
    {
        return round(($percentAmount / 100) * $amount, 2);
    }

    public static function getDayDiff($currentDate, $lastDate): float
    {
        $lastDate = strtotime($lastDate);
        $currentDate = strtotime($currentDate);

        $diff = $currentDate - $lastDate;
        return round($diff / (60 * 60 * 24));
    }
}