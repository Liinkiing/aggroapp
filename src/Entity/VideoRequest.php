<?php

namespace App\Entity;

use App\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRequestRepository")
 */
class VideoRequest
{
    use Timestampable;

    /**
     * @ORM\Id()
     * @Groups({"api"})
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"api"})
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $downloadUrl;

    /**
     * @ORM\Column(type="boolean")
     */
    private $processed = false;

    /**
     * @Groups({"api"})
     * @ORM\Column(type="string", length=255)
     */
    private $replyUrl;

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

    public function getDownloadUrl(): ?string
    {
        return $this->downloadUrl;
    }

    public function setDownloadUrl(?string $downloadUrl): self
    {
        $this->downloadUrl = $downloadUrl;

        return $this;
    }

    public function getProcessed(): ?bool
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
}
