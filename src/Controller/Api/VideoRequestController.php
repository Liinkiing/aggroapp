<?php


namespace App\Controller\Api;

use App\Controller\ApiController;
use App\Entity\VideoRequest;
use App\Form\VideoRequestType;
use App\Message\Command\ProcessTwitterVideo;
use App\Repository\VideoRequestRepository;
use App\Serializer\FormErrorsSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     */
    public function index(): Response
    {
        return $this->json(
            $this->repository->findAll()
        );
    }

    /**
     * @IsGranted({"ROLE_API"})
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
