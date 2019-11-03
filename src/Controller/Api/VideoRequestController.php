<?php


namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\VideoRequest;
use App\Form\VideoRequestType;
use App\Message\Command\ProcessTwitterVideo;
use App\Repository\VideoRequestRepository;
use App\Serializer\FormErrorsSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/video")
 */
class VideoRequestController extends ApiController
{

    private $repository;
    private $bus;

    public function __construct(FormErrorsSerializer $formErrorsSerializer, VideoRequestRepository $repository, MessageBusInterface $bus)
    {
        parent::__construct($formErrorsSerializer);
        $this->repository = $repository;
        $this->bus = $bus;
    }

    /**
     * @Route("/requests", name="api.video_request.index", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the video requests that has been made",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=VideoRequest::class, groups={"api"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="tweet_url",
     *     in="query",
     *     type="string",
     *     description="The field used for filtering by tweet url"
     * )
     */
    public function index(Request $request): Response
    {
        $tweetUrl = $request->query->get('tweet_url');

        return $this->json(
            $tweetUrl ?
                $this->repository->findBy(compact('tweetUrl')) :
                $this->repository->findAll()
        );
    }

    /**
     * @SWG\Response(
     *     response=200,
     *     description="Returns a video request",
     *     @Model(type=VideoRequest::class, groups={"api"})
     * )
     * @Route("/request/{id}", name="api.video_request.show", methods={"GET"})
     */
    public function show(VideoRequest $videoRequest): Response
    {
        return $this->json(
            $videoRequest
        );
    }

    /**
     * @IsGranted({"ROLE_API"})
     * @SWG\Response(
     *     response=201,
     *     description="Returns the newly created video request",
     *     @Model(type=VideoRequest::class, groups={"api"})
     * )
     * @SWG\Parameter(
     *     name="form",
     *     in="body",
     *     description="The form used for this request",
     *     @Model(type=VideoRequestType::class)
     * )
     * @Route("/requests", name="api.video_request.new", methods={"POST"})
     */
    public function new(Request $request, FormFactoryInterface $formFactory, EntityManagerInterface $em): Response
    {
        $videoRequest = new VideoRequest();
        $form = $formFactory->create(VideoRequestType::class, $videoRequest);

        $form->submit(
            json_decode($request->getContent(), true)
        );

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($videoRequest);
            $em->flush();

            $this->bus->dispatch(
                new ProcessTwitterVideo(
                    $videoRequest->getId()
                )
            );

            return $this->json(
                $videoRequest,
                Response::HTTP_CREATED
            );
        }
        return $this->json(
            [
                'errors' => $this->createFormErrors($form)
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

}
