<?php

namespace System\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Users security controller.
 */
class SecurityController extends ContainerAware
{
	/**
     * Login
     *
     * @Route("/sign_in", name="login")
     * @Template()
     */
    public function loginAction()
    {
        if ($this->container->get('security.context')->isGranted('ROLE_CLIENT')) {
            echo 'Hi'; exit;
            return new Response('redirect');
        }

        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session */

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        return array(
        	'last_username' => $lastUsername,
            'error'         => $error,
        );
    }


    /**
     * Login redirect
     *
     * @Route("/login-redirect", name="login_redirect")
     */
    public function redirectAction()
    {   
        if ($this->container->get('security.context')->isGranted('ROLE_EMPLOYEE')) {
            return new RedirectResponse($this->container->get('router')->generate('backoffice_browse'));
        } 
    }

	/**
     * Login check
     *
     * @Route("/login_check", name="login_check")
     */
    public function checkAction()
    {
    	throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }


	/**
     * Logout
     *
     * @Route("/logout", name="logout")
     */
	public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
