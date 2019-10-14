<?php


namespace App\Client;


use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class TwitterClient
{

    public const BASE_URL = 'https://api.twitter.com/1.1/';

    private $client;
    private $bearerToken;

    public function __construct(HttpClientInterface $client, string $bearerToken)
    {
        $this->client = $client;
        $this->bearerToken = $bearerToken;
    }

    public function tweet(int $id): array
    {
        $query = compact('id');
        $response = $this->makeRequest('statuses/show.json', $query);

        return $response->toArray();
    }

    private function makeRequest(string $path, array $query = [], ?string $method = 'GET', ?array $headers = []): ResponseInterface
    {
        $headers = array_merge(
            $headers,
            [
                'Authorization' => 'Bearer ' . $this->bearerToken
            ]
        );        $options = array_merge(
            compact('headers'),
            compact('query')
        );

        return $this->client->request($method, $this->endpoint($path), $options);
    }

    private function endpoint(string $path): string
    {
        return self::BASE_URL . $path;
    }

}
