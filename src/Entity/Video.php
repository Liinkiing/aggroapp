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
    public const VIDEO_STORAGE_DIR = 'videos/';
    public const THUMBNAIL_STORAGE_DIR = 'thumbnails/';

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
     * @Groups({"api"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $thumbnail;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\VideoRequest", inversedBy="video", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $request;

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
        return (self::VIDEO_STORAGE_DIR ?? '') . $this->filename;
    }

    public function getThumbnailPath(): ?string
    {
        return (self::THUMBNAIL_STORAGE_DIR ?? '') . $this->thumbnail;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
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
}
