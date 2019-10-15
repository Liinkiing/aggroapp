<?php


namespace App\MessageHandler\Command;


use App\Client\TwitterClient;
use App\Downloader\TwitterVideoDownloader;
use App\Message\Command\ProcessTwitterVideo;
use App\Model\Tweet;
use App\Repository\VideoRequestRepository;
use DG\Twitter\Twitter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\Exception\NotFoundResourceException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProcessTwitterVideoHandler implements MessageHandlerInterface
{
    private const AVAILABLE_TWEETS_TEXT = [
        'ready.enthusiast',
        'ready.squared',
        'ready.fast',
        'ready.discret'
    ];

    private $downloader;
    private $client;
    private $repository;
    private $em;
    private $twitterClient;
    private $router;
    private $translator;

    public function __construct(
        TwitterClient $client,
        Twitter $twitterClient,
        TranslatorInterface $translator,
        RouterInterface $router,
        TwitterVideoDownloader $downloader,
        VideoRequestRepository $repository,
        EntityManagerInterface $em
    ) {
        $this->downloader = $downloader;
        $this->client = $client;
        $this->repository = $repository;
        $this->em = $em;
        $this->twitterClient = $twitterClient;
        $this->router = $router;
        $this->translator = $translator;
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

                $this->twitterClient->send(
                    $this->translator->trans(self::AVAILABLE_TWEETS_TEXT[array_rand(self::AVAILABLE_TWEETS_TEXT)], [
                        '{downloadUrl}' => $this->router->generate('video_request.download', [
                            'id' => $request->getId()
                        ], RouterInterface::ABSOLUTE_URL)
                    ], 'tweets'),
                    null,
                    [
                        'in_reply_to_status_id' => Tweet::extractIdFromUrl($request->getReplyUrl()),
                        'auto_populate_reply_metadata' => true
                    ]
                );

                $this->em->flush();
            } else {
                // The requested tweet did not have any videos attached, so we can delete the request
                $this->em->remove($request);
                $this->em->flush();
            }
        } else {
            throw new NotFoundResourceException(
                sprintf('Could not find VideoRequest with ID "%s"', $message->getRequestId())
            );
        }
    }

}
