<?php


namespace App\Client;

use Predis\ClientInterface;

class RedisClient
{
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

}
