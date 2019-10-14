<?php

namespace App\EventListener;

use App\Entity\ApiUser;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function prePersist(ApiUser $user, LifecycleEventArgs $event): void
    {
        if ($user->getPlainPassword()) {
            $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
            $user
                ->setPlainPassword(null)
                ->setPassword($password);
        }
    }
}
