<?php


namespace App\Downloader;


use App\Entity\Video;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use Mimey\MimeTypes;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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

        $disposition = HeaderUtils::makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename
        );

        $this->s3Filesystem->putStream(Video::STORAGE_DIR . $filename, $stream, [
            'visibility' => AdapterInterface::VISIBILITY_PUBLIC,
            'ContentDisposition' => $disposition,
        ]);

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $filename;
    }
}
