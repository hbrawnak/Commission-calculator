<?php


namespace Paysera\CommissionTask\API;


use GuzzleHttp\Client;
use Paysera\CommissionTask\Exception\UtilityException;

class CurrencyExchangeClient
{

    const ACCESS_KEY = '171af37d0bd4bf65f457adb8b0d3a759';
    private $client;
    private $access_key;
    private $fromCurrency;
    private $toCurrency;
    private $amount;

    /**
     * CurrencyExchangeClient constructor.
     */
    public function __construct($fromCurrency, $toCurrency, $amount)
    {
        $this->access_key   = self::ACCESS_KEY;
        $this->client       = new Client();
        $this->fromCurrency = $fromCurrency;
        $this->toCurrency   = $toCurrency;
        $this->amount       = $amount;
    }

    public function get()
    {
        try {
            $url      = "http://api.exchangeratesapi.io/v1/convert?access_key={$this->access_key}&from={$this->fromCurrency}&to={$this->toCurrency}&amount={$this->amount}";
            $response = $this->client->request('GET', $url);
            return $response->getBody();
        } catch (\Exception $exception) {
            print_r($exception->getMessage());
            //throw new UtilityException($exception->getMessage());
        }

    }
}