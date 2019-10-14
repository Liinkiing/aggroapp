<?php


namespace App\Downloader;


interface DownloaderInterface
{
    public function download(string $uri);
}
