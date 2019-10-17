<?php


namespace App\Downloader;


use Mimey\MimeTypes;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class BaseDownloader implements DownloaderInterface
{
    protected $client;
    private $mimes;

    protected function getFileExtension(string $mimeType): string
    {
        return $this->mimes->getExtension($mimeType);
    }

    public function __construct(HttpClientInterface $client, MimeTypes $mimes)
    {
        $this->client = $client;
        $this->mimes = $mimes;
    }

    protected function createFilename(string $name, string $mimeType, ?string $directory = null): string
    {
        return ($directory ?? '') . $name . '.' . $this->getFileExtension($mimeType);
    }

    abstract public function download(string $uri): string;

}
