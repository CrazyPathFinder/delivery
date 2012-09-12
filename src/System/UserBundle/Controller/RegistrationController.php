<?php
namespace System\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAware;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;
use System\UserBundle\Form\RegistrationType;
use System\UserBundle\Entity\UserProfile;

/**
 * Users registration controller.
 *
 * @Route("/registration")
 */
class RegistrationController extends Controller
{
	/**
     * Registration
     *
     * @Route("/", name="registration")
     * @Template()
     */
    public function registrationAction()
    {
    	$entity = new UserProfile();
        $form   = $this->createForm(new RegistrationType(), $entity);
        $formHandler = $this->container->get('userprofile.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
        $process = $formHandler->process($form, $confirmationEnabled);

        if ($process) {
            $userProfile = $form->getData();
            $user = $userProfile->getUser();
            if ($confirmationEnabled) {
                $this->container->get('session')->set('registration_user_send_confirmation_email/email', $user->getEmail());
                $route = 'registration_check_email';
            } else {

                // Send mail
                
                $mailer = $this->container->get('mailer');
                $router = $this->container->get('router');
                $templating = $this->container->get('templating');

                $email = $user->getEmail();
                
                //$em = $this->container->get('doctrine.orm.entity_manager');
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Register success');
                    
                $message->setFrom(array('bot@ssp-soft.by' => 'webmaster'));

                $message->setTo($email)
                    ->setBody(
                        $templating->render('SystemUserBundle:Registration:registrationSuccessEmail.html.twig', 
                        array(
                            'user' => $user)
                        ), 'text/html', 'utf-8'
                    );
                
                $mailer->send($message); 
                $this->authenticateUser($user);

                return new RedirectResponse($this->container->get('router')->generate('backoffice_main_page'));


                
            }
            $url = $this->container->get('router')->generate($route);
            return new RedirectResponse($url);
        }

        return array(
        	'form' => $form->createView()
        );
    }

    /**
     * Authenticate a user with Symfony Security
     *
     * @param UserInterface $user
     */
    protected function authenticateUser(UserInterface $user)
    {
    	$providerKey = $this->container->getParameter('fos_user.firewall_name');
    	$token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

    	$this->container->get('security.context')->setToken($token);
    }

    /**
     * Tell the user to check his email provider
     *
     * @Route("/check-email", name="registration_check_email")
     * @Template()
     */
    public function checkEmailAction()
    {
    	$email = $this->container->get('session')->get('registration_user_send_confirmation_email/email');
    	$this->container->get('session')->remove('registration_user_send_confirmation_email/email');
    	$user = $this->container->get('fos_user.user_manager')->findUserByEmail($email);

    	if (null === $user) {
    		throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
    	}

    	return array(
    		'user' => $user
    	);
    }

	/**
     * Receive the confirmation token from user email provider, login the user
     *
     * @Route("/confirm/{token}", name="fos_user_registration_confirm")
     */
    public function confirmAction($token)
    {
        $user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        $user->setConfirmationToken(null);
        $user->setEnabled(true);
        $user->setLastLogin(new \DateTime());

        $this->container->get('fos_user.user_manager')->updateUser($user);
        $this->authenticateUser($user);

        return new RedirectResponse($this->container->get('router')->generate('fos_user_registration_confirmed'));
    }

    /**
     * Tell the user his account is now confirmed
     *
     * @Route("/confirmed", name="fos_user_registration_confirmed")
     * @Template()
     */
    public function confirmedAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return array(
        	'user' => $user
        );
    }

    /**
     * Ajax check unique email
     *
     * @Route("/validate/check-unique-email", name="ajax_check_unique_email")
     */
    public function validateCheckEmailAction()
    {
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('This is not async query.');
        }
                       
           
        $em = $this->getDoctrine()->getEntityManager();

        $fieldId = $request->request->get('fieldId');
        $fieldValue = $request->request->get('fieldValue');
        if ( !$fieldValue ) {
            return new Response(
                json_encode(array(
                    //$fieldId,
                     false
                ))
            );
        }

        $user = $em->getRepository('SystemUserBundle:User')
            ->findOneBy(array(
                'email' => $fieldValue
            ))
        ;

        return new Response(
            json_encode(array(
                //$fieldId, 
                $user ? false : true
            ))
        );
    }

    /**
     * Ajax check unique username
     *
     * @Route("/validate/check-unique-username", name="ajax_check_unique_username")
     */
    public function validateCheckUsernameAction()
    {
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException('This is not async query.');
        }
        
        $em = $this->getDoctrine()->getEntityManager();

        $fieldId = $request->query->get('fieldId');
        $fieldValue = $request->query->get('fieldValue');

        if ( !$fieldValue ) {
            return new Response(
                json_encode(array(
                    $fieldId, 
                    false
                ))
            );
        }

        $user = $em->getRepository('SystemUserBundle:User')
            ->findOneBy(array(
                'username' => $fieldValue
            ))
        ;

        return new Response(
            json_encode(array(
                $fieldId, 
                $user ? false : true
            ))
        );
    }
}
