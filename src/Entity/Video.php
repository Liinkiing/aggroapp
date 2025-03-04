<?php

namespace App\Entity;

use App\Traits\Timestampable;
use App\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class Video
{
    public const STORAGE_DIR = 'videos/';

    use Timestampable;
    use UuidTrait;

    /**
     * @Groups({"api"})
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @Groups({"api"})
     * @ORM\Column(type="string", length=255)
     */
    private $mimeType;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\VideoRequest", inversedBy="video", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $request;

    /**
     * @Groups({"api"})
     * @ORM\OneToOne(targetEntity="App\Entity\VideoThumbnail", cascade={"persist", "remove"})
     */
    private $thumbnail;

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->filename ? (self::STORAGE_DIR ?? '') . $this->filename : null;
    }

    public function getRequest(): ?VideoRequest
    {
        return $this->request;
    }

    public function setRequest(VideoRequest $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getThumbnail(): ?VideoThumbnail
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?VideoThumbnail $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}
