<?php

namespace App\Security;

use App\Repository\ApiUserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiAuthenticator extends AbstractGuardAuthenticator
{
    private const HEADER_AUTH_NAME = 'X-AUTH-TOKEN';

    private $userRepository;
    private $translator;

    public function __construct(ApiUserRepository $userRepository, TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    public function supports(Request $request)
    {
        return $request->headers->has(self::HEADER_AUTH_NAME);
    }

    public function getCredentials(Request $request)
    {
        return [
            'token' => $request->headers->get(self::HEADER_AUTH_NAME),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiToken = $credentials['token'];

        if (null === $apiToken) {
            return;
        }

        return $this->userRepository
            ->findOneBy(['apiToken' => $apiToken]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'error' => $this->translator->trans('error.invalid_credentials')
        ], Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse([
            'error' => $this->translator->trans('error.auth_needed')
        ], Response::HTTP_FORBIDDEN);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
