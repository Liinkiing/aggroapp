<?php


namespace App\Controller;


use App\Entity\VideoRequest;
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
     * @Route("/video/{id}/download", name="video_request.download", methods={"GET"})
     */
    public function download(VideoRequest $videoRequest, S3Client $client, string $twitterVideosS3BucketName): Response
    {
        if ($videoRequest->isProcessed()) {
            $disposition = HeaderUtils::makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $videoRequest->getFilename()
            );

            $command = $client->getCommand('GetObject', [
                'Bucket' => $twitterVideosS3BucketName,
                'Key' => $videoRequest->getFilename(),
                'ResponseContentType' => $videoRequest->getMimeType(),
                'ResponseContentDisposition' => $disposition,
            ]);

            $request = $client->createPresignedRequest($command, '+1 hour');

            return new RedirectResponse((string) $request->getUri());
        }

        throw $this->createNotFoundException();
    }

}
