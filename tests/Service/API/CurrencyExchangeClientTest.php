<?php


namespace App\CommissionTask\Tests\Service\API;


use App\CommissionTask\Service\API\CurrencyExchangeClient;
use PHPUnit\Framework\TestCase;

class CurrencyExchangeClientTest extends TestCase
{
    private $exchange;

    protected function setUp()
    {
        $this->exchange = new CurrencyExchangeClient('EUR', 'JPY', 1);
    }

    public function testResponse()
    {
        $output = $this->exchange->get();
        $this->assertArrayHasKey('result', $output);
    }
}