<?php

namespace System\UserBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;

class UserCreator
{
    public function prePersist(LifecycleEventArgs $args) {

        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        if ($entity instanceof \System\UserBundle\Entity\UserProfile) {
            $user = $entity->getUser();
            $entityManager->persist($user);
            $entityManager->flush();
            $entity->setUser($user);
        }
    }
}
