<?php


namespace App\Client;

use App\Entity\Video;
use Predis\ClientInterface;

class RedisClient
{
    private $client;
    private const DOWNLOADS_COUNT_PREFIX_KEY = 'count:downloads';

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function incrementVideoDownloads(Video $video): int
    {
        return $this->client->incr(self::DOWNLOADS_COUNT_PREFIX_KEY . ':' . $video->getId()) ?? 0;
    }

    public function getVideoDownloads(Video $video): int
    {
        return (int)(($this->client->get(self::DOWNLOADS_COUNT_PREFIX_KEY . ':' . $video->getId())) ?? 0);
    }
}
