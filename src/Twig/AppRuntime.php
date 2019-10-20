<?php


namespace App\Twig;


use Symfony\Component\Asset\Context\RequestStackContext;
use Twig\Extension\RuntimeExtensionInterface;

class AppRuntime implements RuntimeExtensionInterface
{

    private const S3_HOST = 's3.amazonaws.com';

    private $requestStackContext;
    private $s3BucketName;
    private $assetsCdn;

    public function __construct(RequestStackContext $requestStackContext, string $s3BucketName, ?string $assetsCdn = null)
    {
        $this->requestStackContext = $requestStackContext;
        $this->s3BucketName = $s3BucketName;
        $this->assetsCdn = $assetsCdn;
    }

    public function generatePublicPath(string $path): string
    {
        $basePath = $this->assetsCdn ?:
            'https://' . $this->s3BucketName . '.' . self::S3_HOST;
        $fullPath = $basePath . '/' . $path;

        if (strpos($fullPath, '://') !== false) {
            return $fullPath;
        }

        return $this->requestStackContext
                ->getBasePath() . $fullPath;
    }
}
