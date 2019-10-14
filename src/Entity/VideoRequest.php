<?php

namespace App\Entity;

use App\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRequestRepository")
 */
class VideoRequest
{
    use Timestampable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tweetUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $requestedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTweetUrl(): ?string
    {
        return $this->tweetUrl;
    }

    public function setTweetUrl(string $tweetUrl): self
    {
        $this->tweetUrl = $tweetUrl;

        return $this;
    }

    public function getRequestedBy(): ?string
    {
        return $this->requestedBy;
    }

    public function setRequestedBy(string $requestedBy): self
    {
        $this->requestedBy = $requestedBy;

        return $this;
    }
}
