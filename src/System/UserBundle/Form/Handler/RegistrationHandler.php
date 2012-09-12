<?php
namespace System\UserBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use System\UserBundle\Entity\UserProfile;
use System\UserBundle\Manager\AbstractUserManager;

class RegistrationHandler
{
    protected $request;
    protected $userManager;
    private $container;

    public function __construct(Request $request, AbstractUserManager $userManager, ContainerInterface $container)
    {
        $this->request = $request;
        $this->userManager = $userManager;
        $this->container = $container;
    }

    public function process($form, $confirmation = false)
    {
        $user = $this->userManager->createUser();
        $userProfile = new UserProfile();
        $userProfile->setUser($user);
        
        $form->setData($userProfile);

        if ('POST' == $this->request->getMethod()) {
            $form->bindRequest($this->request);

            if ($form->isValid()) {
                $userProfile = $form->getData();
                $user = $userProfile->getUser();
                $user = $this->userManager->updateUser($user);
                $this->onSuccess($userProfile, $confirmation);

                return true;
            }
        }

        return false;
    }

    protected function onSuccess($userProfile, $confirmation)
    {
        if ($confirmation) {
            $userProfile->getUser()->setEnabled(false);
        } else {
            $userProfile->getUser()->setConfirmationToken(null);
            $userProfile->getUser()->setEnabled(true);
        }

        $userProfile->getUser()->addRole('ROLE_EMPLOYEE');

        $em = $this->container->get('doctrine.orm.entity_manager');
        //var_dump($userProfile->getUser()); exit;
        $em->persist($userProfile);
        $em->flush();
    }
}
