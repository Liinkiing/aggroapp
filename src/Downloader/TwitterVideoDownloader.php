<?php


namespace App\Downloader;


use App\Entity\Video;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use Mimey\MimeTypes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitterVideoDownloader extends BaseDownloader
{
    private $s3Filesystem;

    public function __construct(HttpClientInterface $client, MimeTypes $mimes, FilesystemInterface $s3Filesystem)
    {
        parent::__construct($client, $mimes);
        $this->s3Filesystem = $s3Filesystem;
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

        $this->s3Filesystem->writeStream(Video::VIDEO_STORAGE_DIR . $filename, $stream, [
            'visibility' => AdapterInterface::VISIBILITY_PUBLIC
        ]);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $filename;
    }
}
