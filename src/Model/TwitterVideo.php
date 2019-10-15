<?php


namespace App\Model;


class TwitterVideo implements ApiModel
{

    public const TYPE = 'video';

    private $id;
    private $url;
    private $duration;
    private $bitrate;
    private $mimeType;

    public static function fromApi(array $response): self
    {
        $variants = array_filter($response['video_info']['variants'], static function (array $variant) {
            return isset($variant['bitrate']);
        });
        usort($variants, static function (array $a, array $b) {
            return $a['bitrate'] <=> $b['bitrate'];
        });

        $bestVariant = $variants[0];

        return new self(
            $response['id'],
            $bestVariant['url'],
            $response['video_info']['duration_millis'],
            $bestVariant['bitrate'],
            $bestVariant['content_type']
        );
    }

    private function __construct(int $id, string $url, int $duration, int $bitrate, string $mimeType)
    {
        $this->id = $id;
        $this->url = $url;
        $this->duration = $duration;
        $this->bitrate = $bitrate;
        $this->mimeType = $mimeType;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getBitrate(): int
    {
        return $this->bitrate;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }



}
