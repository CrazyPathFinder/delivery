<?php

namespace System\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\User\UserInterface;
use System\UserBundle\Form\ResettingType;
use System\UserBundle\Form\Model\ChangePassword;
/**
 * Controller managing the resetting of the password
 *
 * @Route("/user/reset-password")
 */
class ResettingController extends Controller
{
    /**
     * Request reset user password: show form
     *
     * @Route("/request", name="reset_password_request")
     * @Template()
     */
    public function requestAction()
    {
    	return array();
    }

    /**
     * Request reset user password: submit form and send email
     *
     * @Route("/send-email", name="reset_password_send_email")
     * @Method({"POST"})
     */
    public function sendEmailAction()
    {
        $username = $this->container->get('request')->request->get('username');

        $user = $this->container->get('system.user_manager')->findUserByEmail($username);

        if (null === $user){
            return $this->container->get('templating')->renderResponse('SystemUserBundle:Resetting:request.html.twig', array('invalid_username' => $username));
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return $this->container->get('templating')->renderResponse('SystemUserBundle:Resetting:passwordAlreadyRequested.html.twig');
        }
        


        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $user->setConfirmationToken($tokenGenerator->generateToken());
        $this->container->get('session')->set('fos_user_send_resetting_email/email', $user->getEmail());

        // Отправка Email на сброс пароля
        $mailer = $this->container->get('mailer');
        $router = $this->container->get('router');
        $templating = $this->container->get('templating');

        $email = $user->getEmail();
        
        $em = $this->container->get('doctrine.orm.entity_manager');
        
        $url = $router->generate('system_user_resetting_reset', array('token' => $user->getConfirmationToken()), true);  
        
        $spath = "http://".$_SERVER['SERVER_NAME'];
                
        $message = \Swift_Message::newInstance()
            ->setSubject('Reset password');
            
        $message->setFrom(array('bot@ssp-soft.by' => 'webmaster'));

        $message->setTo($email)
            ->setBody(
                $templating->render('SystemUserBundle:Registration:resettemp.html.twig', 
                array(
                    'spath' => $spath, 
                    'confirmationUrl' => $url, 
                    'user' => $user)
                ), 'text/html', 'utf-8'
            );
        
        $mailer->send($message);


        //$this->container->get('userprofile.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('system.user_manager')->updateUser($user);

        return new RedirectResponse($this->container->get('router')->generate('reset_password_check_email'));
    }

    /**
     * Tell the user to check his email provider
     *
     * @Route("/check-email", name="reset_password_check_email")
     * @Template()
     */
    public function checkEmailAction()
    {
        $session = $this->container->get('session');
        $email = $session->get('fos_user_send_resetting_email/email');
        $session->remove('fos_user_send_resetting_email/email');
        $user = $this->container->get('system.user_manager')->findUserByEmail($email);
        if (empty($user)) {
            return new RedirectResponse($this->container->get('router')->generate('reset_password_request'));
        }

        return array(
            'user' => $user,
        );
    }

    /**
     * Reset user password
     *
     * @Route("/{token}", name="system_user_resetting_reset")
     * @Template()
     */
    public function resetAction($token)
    {
        $request = $this->getRequest();

        $userManager = $this->container->get('system.user_manager');
        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user){
            throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
        }

        if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return new RedirectResponse($this->container->get('router')->generate('reset_password_request'));
        }
        
        $passwordEntity = new ChangePassword();
        $form = $this->createForm(new ResettingType(), $passwordEntity);
       

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {

                $user->setPlainPassword($form->getData()->new);
                $user->setConfirmationToken(null);
                $user->setPasswordRequestedAt(null);
                $user->setEnabled(true);
                $userManager->updateUser($user);
                
                // Send mail
                
                $mailer = $this->container->get('mailer');
                $router = $this->container->get('router');
                $templating = $this->container->get('templating');

                $email = $user->getEmail();
                
                $em = $this->container->get('doctrine.orm.entity_manager');
                
                $message = \Swift_Message::newInstance()
                    ->setSubject('Change password');
                    
                $message->setFrom(array('bot@ssp-soft.by' => 'webmaster'));

                $message->setTo($email)
                    ->setBody(
                        $templating->render('SystemUserBundle:Resetting:passwordChangeEmail.html.twig', 
                        array(
                            'user' => $user)
                        ), 'text/html', 'utf-8'
                    );
                
                $mailer->send($message); 
                $this->authenticateUser($user);
                return new RedirectResponse($this->container->get('router')->generate('login'));
            }
        }

        return array(
            'token' => $token,
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
}
