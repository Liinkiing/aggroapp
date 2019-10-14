<?php


namespace App\Model;


class TwitterVideo implements ApiModel
{

    public const TYPE = 'video';

    private $id;
    private $url;
    private $duration;

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
            $response['video_info']['duration_millis']
        );
    }

    private function __construct(int $id, string $url, int $duration)
    {
        $this->id = $id;
        $this->url = $url;
        $this->duration = $duration;
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

}
