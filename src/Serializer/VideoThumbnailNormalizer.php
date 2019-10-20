<?php


namespace App\Serializer;


use App\Entity\VideoThumbnail;
use App\Twig\AppRuntime;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class VideoThumbnailNormalizer implements ContextAwareNormalizerInterface
{

    private $router;
    private $normalizer;
    private $appExtension;

    public function __construct(ObjectNormalizer $normalizer, AppRuntime $appExtension)
    {
        $this->normalizer = $normalizer;
        $this->appExtension = $appExtension;
    }

    public function supportsNormalization($data, $format = null, array $context = []): bool
    {
        return $data instanceof VideoThumbnail;
    }

    public function normalize($videoThumbnail, $format = null, array $context = [])
    {
        /** @var VideoThumbnail $videoThumbnail */
        $data = $this->normalizer->normalize($videoThumbnail, $format, $context);

        $groups =
            isset($context['groups']) && \is_array($context['groups']) ? $context['groups'] : [];

        if (\in_array('api', $groups, true)) {
            $data['_href'] = [
                'show' => $this->appExtension->generatePublicPath(
                    $videoThumbnail->getPath()
                )
            ];
        }

        return $data;
    }
}
