<?php


namespace App\CommissionTask\Service\API;


interface ClientInterface
{
    public function request($method, $url);
}