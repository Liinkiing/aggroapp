<?php


namespace App\Downloader;


use Mimey\MimeTypes;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class BaseDownloader implements DownloaderInterface
{
    protected $client;
    private $mimes;
    private $saveDir;

    private function prepare(): void
    {
        if (!is_dir($this->saveDir) && !mkdir($this->saveDir, 0755, true) && !is_dir($this->saveDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->saveDir));
        }
    }

    protected function getFileExtension(string $mimeType): string
    {
        return $this->mimes->getExtension($mimeType);
    }

    public function __construct(HttpClientInterface $client, MimeTypes $mimes, string $saveDir)
    {
        $this->client = $client;
        $this->mimes = $mimes;
        $this->saveDir = $saveDir;
        $this->prepare();
    }

    public function createFilename(string $baseDir, string $name, string $mimeType): string
    {
        return $baseDir . DIRECTORY_SEPARATOR . $name . '.' . $this->getFileExtension($mimeType);
    }

    public function getSaveDir(): string
    {
        return $this->saveDir;
    }

    abstract public function download(string $uri): void;

}
