<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace System\UserBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use System\UserBundle\Entity\User;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserManagerInterface $userManager)
    {
        
        $this->userManager = $userManager;
    }

    /**
     * {@inheritDoc}
     */
    public function loadUserByEmail($email)
    {
        $user = $this->findUser($email);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Email "%s" does not exist.', $email));
        }

        return $user;
    }

    public function loadUserByUsername($email)
    {
        $user = $this->findUser($email);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('Email "%s" does not exist.', $email));
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(SecurityUserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Expected an instance of System\UserBundle\Entity\User, but got "%s".', get_class($user)));
        }

        if (null === $reloadedUser = $this->userManager->findUserBy(array('id' => $user->getId()))) {
            throw new UsernameNotFoundException(sprintf('User with ID "%d" could not be reloaded.', $user->getId()));
        }

        return $reloadedUser;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }

    /**
     * Finds a user by username.
     *
     * This method is meant to be an extension point for child classes.
     *
     * @param string $username
     *
     * @return UserInterface|null
     */
    protected function findUser($email)
    {
        return $this->userManager->findUserByEmail($email);
    }
}