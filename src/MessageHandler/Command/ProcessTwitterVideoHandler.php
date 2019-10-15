<?php


namespace App\MessageHandler\Command;


use App\Client\TwitterClient;
use App\Downloader\TwitterVideoDownloader;
use App\Message\Command\ProcessTwitterVideo;
use App\Model\Tweet;
use App\Repository\VideoRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProcessTwitterVideoHandler implements MessageHandlerInterface
{

    private $downloader;
    private $client;
    private $repository;
    private $em;

    public function __construct(
        TwitterClient $client,
        TwitterVideoDownloader $downloader,
        VideoRequestRepository $repository,
        EntityManagerInterface $em
    ) {
        $this->downloader = $downloader;
        $this->client = $client;
        $this->repository = $repository;
        $this->em = $em;
    }

    public function __invoke(ProcessTwitterVideo $message)
    {
        $request = $this->repository->find($message->getRequestId());

        if ($request && !$request->isProcessed()) {
            $tweet = Tweet::fromApi(
                $this->client->tweet(
                    Tweet::extractIdFromUrl($request->getTweetUrl())
                )
            );

            if ($tweet->getVideos()->count() > 0) {
                $video = $tweet->getVideos()->first();
                $filename = $this->downloader->download(
                    $video->getUrl()
                );

                $request
                    ->setProcessed(true)
                    ->setMimeType($video->getMimeType())
                    ->setFilename($filename);
            } else {
                // The requested tweet did not have any videos attached, so we can delete the request
                $this->em->remove($request);
            }

            $this->em->flush();
        } else {
            throw new NotFoundResourceException(
                sprintf('Could not find VideoRequest with ID "%s"', $message->getRequestId())
            );
        }
    }

}
