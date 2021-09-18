<?php


namespace App\CommissionTask\Service\API;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use App\CommissionTask\Exception\UtilityException;

class CurrencyExchangeClient extends APIClient
{
    const ACCESS_KEY = 'c2b26729318d0bf4916e48bcc2649064';

    private $access_key;
    private $fromCurrency;
    private $toCurrency;
    private $amount;

    private $base;

    /**
     * CurrencyExchangeClient constructor.
     */
    public function __construct($fromCurrency, $toCurrency, $amount)
    {
        $this->access_key   = self::ACCESS_KEY;
        $this->fromCurrency = $fromCurrency;
        $this->toCurrency   = $toCurrency;
        $this->amount       = $amount;

        $this->base = "http://api.exchangeratesapi.io/v1/";
    }

    /**
     * @return mixed
     * @throws UtilityException
     * @throws GuzzleException
     */
    public function get()
    {
        try {
            $url      = $this->base . "convert?access_key={$this->access_key}&from={$this->fromCurrency}&to={$this->toCurrency}&amount={$this->amount}";
            $response = $this->request('GET', $url);
            return json_decode($response->getBody(), true);
        } catch (\Exception $exception) {
            throw new UtilityException($exception->getMessage());
        }
    }
}