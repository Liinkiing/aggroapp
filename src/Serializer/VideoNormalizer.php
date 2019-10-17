<?php


namespace App\Serializer;


use App\Entity\Video;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class VideoNormalizer implements ContextAwareNormalizerInterface
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
        return $data instanceof Video;
    }

    public function normalize($video, $format = null, array $context = [])
    {
        /** @var Video $video */
        $data = $this->normalizer->normalize($video, $format, $context);

        $groups =
            isset($context['groups']) && \is_array($context['groups']) ? $context['groups'] : [];

        if (\in_array('api', $groups, true)) {
            $data['_href'] = [
                'download' => $this->router->generate('video.download', [
                    'id' => $video->getId()
                ], RouterInterface::ABSOLUTE_URL)
            ];
        }

        return $data;
    }
}
