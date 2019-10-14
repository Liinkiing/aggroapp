<?php


namespace App\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Tweet implements ApiModel
{

    private $id;
    private $text;
    private $videos;

    public static function fromApi(array $response): self
    {
        return new self(
            $response['id'],
            $response['text'],
            $response['extended_entities'] && $response['extended_entities']['media']
                ? array_map(
                [TwitterVideo::class, 'fromApi'],
                array_filter($response['extended_entities']['media'], static function (array $media) {
                    return $media['type'] === TwitterVideo::TYPE;
                })
            )
                : []
        );
    }

    private function __construct(int $id, string $text, array $videos)
    {
        $this->id = $id;
        $this->text = $text;
        $this->videos = new ArrayCollection($videos);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return TwitterVideo[]|Collection<TwitterVideo>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }


}
