<?php


namespace Paysera\CommissionTask\Service\Charge;


interface ChargeableInterface
{
    public function deposit();

    public function withdraw();
}