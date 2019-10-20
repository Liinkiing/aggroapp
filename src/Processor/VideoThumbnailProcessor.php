<?php


namespace App\Processor;


use App\Entity\Video;
use App\Entity\VideoThumbnail;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\Media\Audio;
use FFMpeg\Media\Frame;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;

class VideoThumbnailProcessor
{
    private const THUMBNAIL_FILE_EXTENSION = 'jpg';

    private $FFMpeg;
    private $s3Filesystem;
    private $logger;

    public function __construct(FFMpeg $FFMpeg, FilesystemInterface $s3Filesystem, LoggerInterface $logger)
    {
        $this->FFMpeg = $FFMpeg;
        $this->s3Filesystem = $s3Filesystem;
        $this->logger = $logger;
    }

    public function generate(Video $file): ?VideoThumbnail
    {
        try {
            $tmpVideo = tmpfile();
            $tmpImageFilename = @tempnam('/tmp', '');
            fwrite($tmpVideo, stream_get_contents($this->s3Filesystem->readStream($file->getPath())));

            $tmpVideoUri = stream_get_meta_data($tmpVideo)['uri'];

            $video = $this->FFMpeg->open($tmpVideoUri);
            $thumbnail = $this->createThumbnail($video);
            $thumbnail->save($tmpImageFilename);

            $filename = $this->createFilename($file);
            $tmpImage = fopen($tmpImageFilename, 'rb+');
            rewind($tmpImage);
            $this->s3Filesystem->putStream(
                VideoThumbnail::STORAGE_DIR . $filename,
                $tmpImage,
                [
                    'visibility' => AdapterInterface::VISIBILITY_PUBLIC
                ]
            );
            if (is_resource($tmpVideo)) {
                fclose($tmpVideo);
            }
            if (is_resource($tmpImage)) {
                fclose($tmpImage);
            }
            @unlink($tmpImageFilename);

            return (new VideoThumbnail())
                ->setFilename($filename)
                ->setMimeType('image/jpeg')
                ;
        } catch (\Exception $ex) {
            $this->logger->error($ex->getMessage());

            return null;
        }
    }

    private function createFilename(Video $file): string
    {
        $parts = explode('.', $file->getFilename());
        array_pop($parts);
        return implode('.', $parts) . '.' . self::THUMBNAIL_FILE_EXTENSION;
    }

    /**
     * @param \FFMpeg\Media\Video|Audio $video
     * @return Frame
     */
    private function createThumbnail($video): Frame
    {
        $duration = (float)$video->getFormat()->get('duration');
        try {
            $frameMoment = random_int(1, $duration);
        } catch (\Exception $e) {
            $frameMoment = 1;
        }
        return $video->frame(TimeCode::fromSeconds($frameMoment));
    }
}
