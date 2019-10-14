<?php


namespace App\Downloader;


use Mimey\MimeTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitterVideoDownloader extends BaseDownloader
{
    public function __construct(HttpClientInterface $client, MimeTypes $mimes, string $twitterVideosDirectory)
    {
        parent::__construct($client, $mimes, $twitterVideosDirectory);
    }

    public function download(string $uri): void
    {
        $response = $this->client->request(Request::METHOD_GET, $uri);

        $stream = fopen($this->createFilename(
            $this->getSaveDir(),
            uniqid('', true),
            $response->getHeaders()['content-type'][0]
        ), 'wb');

        foreach ($this->client->stream($response) as $chunk) {
            fwrite($stream, $chunk->getContent());
        }

        if (is_resource($stream)) {
            fclose($stream);
        }
    }
}
