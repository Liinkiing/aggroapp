<?php


namespace App\Controller\Api;


use App\Controller\ApiController;
use App\Repository\VideoRequestRepository;
use App\Serializer\FormErrorsSerializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/video")
 */
class VideoRequestController extends ApiController
{

    private $repository;

    public function __construct(FormErrorsSerializer $formErrorsSerializer, VideoRequestRepository $repository)
    {
        parent::__construct($formErrorsSerializer);
        $this->repository = $repository;
    }

    /**
     * @Route("/requests", name="video_request.index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->json(
            $this->repository->findAll()
        );
    }

}
