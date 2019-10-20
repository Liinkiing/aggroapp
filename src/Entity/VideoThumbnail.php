<?php

namespace App\Entity;

use App\Traits\Timestampable;
use App\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VideoRepository")
 */
class VideoThumbnail
{
    public const STORAGE_DIR = 'thumbnails/';

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
}
