<?php

namespace App\Controller;


use App\Serializer\FormErrorsSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{

    private $formErrorsSerializer;

    public function __construct(FormErrorsSerializer $formErrorsSerializer)
    {
        $this->formErrorsSerializer = $formErrorsSerializer;
    }

    protected function json($data, int $status = 200, array $headers = array(), array $context = array()): JsonResponse
    {
        if ($this->container->has('serializer')) {
            $json = $this->container->get('serializer')->serialize($data, 'json', array_merge(array(
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ), array_merge($context, ['groups' => ['api']])));

            return new JsonResponse($json, $status, $headers, true);
        }

        return new JsonResponse($data, $status, $headers);
    }

    protected function createFormErrors(FormInterface $form): array
    {
        return $this->formErrorsSerializer->convertFormToArray($form);
    }

}
