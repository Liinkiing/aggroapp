<?php


namespace App\Controller;


use App\Entity\Video;
use Aws\S3\S3Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{

    /**
     * @Route("/video/{id}/download", name="video.download", methods={"GET"})
     */
    public function download(Video $video, S3Client $client, string $twitterVideosS3BucketName): Response
    {
        if ($video->getPath() && $video->getFilename() && $video->getMimeType()) {
            $disposition = HeaderUtils::makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $video->getFilename()
            );

            $command = $client->getCommand('GetObject', [
                'Bucket' => $twitterVideosS3BucketName,
                'Key' => $video->getPath(),
                'ResponseContentType' => $video->getMimeType(),
                'ResponseContentDisposition' => $disposition,
            ]);

            $request = $client->createPresignedRequest($command, '+1 hour');

            return new RedirectResponse((string) $request->getUri());
        }

        throw $this->createNotFoundException();
    }

}
