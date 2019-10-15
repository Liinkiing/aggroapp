<?php


namespace App\Serializer;


use App\Entity\VideoRequest;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class VideoRequestNormalizer implements ContextAwareNormalizerInterface
{

    private $router;
    private $normalizer;

    public function __construct(UrlGeneratorInterface $router, ObjectNormalizer $normalizer)
    {
        $this->router = $router;
        $this->normalizer = $normalizer;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof VideoRequest;
    }

    public function normalize($videoRequest, $format = null, array $context = [])
    {
        /** @var VideoRequest $videoRequest */
        $data = $this->normalizer->normalize($videoRequest, $format, $context);

        $groups =
            isset($context['groups']) && \is_array($context['groups']) ? $context['groups'] : [];

        if (\in_array('api', $groups, true)) {
            $data['_href'] = [
                'self' => $this->router->generate('api.video_request.show', [
                    'id' => $videoRequest->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL),
                'download' => $this->router->generate('video_request.download', [
                    'id' => $videoRequest->getId()
                ], UrlGeneratorInterface::ABSOLUTE_URL)
            ];
        }

        return $data;
    }
}
