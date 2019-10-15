<?php


namespace App\Downloader;


use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use Mimey\MimeTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitterVideoDownloader extends BaseDownloader
{
    private $twitterVideosS3Filesystem;

    public function __construct(HttpClientInterface $client, MimeTypes $mimes, FilesystemInterface $twitterVideosS3Filesystem)
    {
        parent::__construct($client, $mimes);
        $this->twitterVideosS3Filesystem = $twitterVideosS3Filesystem;
    }

    public function download(string $uri): string
    {
        $response = $this->client->request(Request::METHOD_GET, $uri);

        $filename = $this->createFilename(
            uniqid('', true),
            $response->getHeaders()['content-type'][0]
        );

        $stream = tmpfile();

        foreach ($this->client->stream($response) as $chunk) {
            fwrite($stream, $chunk->getContent());
        }

        $this->twitterVideosS3Filesystem->writeStream($filename, $stream, [
            'visibility' => AdapterInterface::VISIBILITY_PUBLIC
        ]);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $filename;
    }
}
