<?php


namespace App\CommissionTask\Service\API;

use GuzzleHttp\Client;

class APIClient implements ClientInterface
{
    public function request($method, $url)
    {
        $client = new Client();
        return $client->request($method, $url);
    }
}