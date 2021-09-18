<?php


namespace App\CommissionTask\Service\API;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class APIClient implements ClientInterface
{
    /**
     * @param $method
     * @param $url
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function request($method, $url)
    {
        $client = new Client();
        return $client->request($method, $url);
    }
}