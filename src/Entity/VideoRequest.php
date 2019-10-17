<?php

namespace App\Entity;

use App\Traits\Timestampable;
use App\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRequestRepository")
 * @UniqueEntity("tweetUrl")
 */
class VideoRequest
{

    use Timestampable;
    use UuidTrait;

    /**
     * @Groups({"api"})
     * @Assert\NotBlank()
     * @Assert\Url()
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $tweetUrl;

    /**
     * @Groups({"api"})
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $requestedBy;

    /**
     * @Groups({"api"})
     * @ORM\Column(type="boolean")
     */
    private $processed = false;

    /**
     * @Groups({"api"})
     * @Assert\NotBlank()
     * @Assert\Url()
     * @ORM\Column(type="string", length=255)
     */
    private $replyUrl;

    /**
     * @Groups({"api"})
     * @ORM\OneToOne(targetEntity="App\Entity\Video", mappedBy="request", cascade={"persist", "remove"})
     */
    private $video;

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

    public function isProcessed(): ?bool
    {
        return $this->processed;
    }

    public function setProcessed(bool $processed): self
    {
        $this->processed = $processed;

        return $this;
    }

    public function getReplyUrl(): ?string
    {
        return $this->replyUrl;
    }

    public function setReplyUrl(string $replyUrl): self
    {
        $this->replyUrl = $replyUrl;

        return $this;
    }

    public function getVideo(): ?Video
    {
        return $this->video;
    }

    public function setVideo(Video $video): self
    {
        $this->video = $video;

        // set the owning side of the relation if necessary
        if ($this !== $video->getRequest()) {
            $video->setRequest($this);
        }

        return $this;
    }

}
