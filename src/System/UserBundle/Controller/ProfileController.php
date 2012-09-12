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
use System\UserBundle\Form\EditProfileType;
use System\UserBundle\Entity\UserProfile;

/**
 * Users registration controller.
 *
 * @Route("/profile")
 */
class ProfileController extends Controller
{
	/**
     * Registration
     *
     * @Route("/edit", name="profile_edit")
     * @Template()
     */
    public function editAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $entity = $em->getRepository('SystemUserBundle:UserProfile')
            ->find( $user->getId() )
        ;
        $baseEntity = clone $entity;
        $baseUser = clone $user;
        $form   = $this->createForm(new EditProfileType(), $entity);
        $form['user']['emailOld']->setData($entity->getUser()->getEmail());
        
        $request = $this->getRequest();
        $changes = array();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                if ($form['user']['email']->getData() != $baseUser->getEmail()) $changes[] = 'email';
                if ($form['userName']->getData() != $baseEntity->getUserName()) $changes[] = 'userName';
                $newPassword = $form['user']['newPassword']->getData();
                if (($newPassword != '') || ($form['user']['email']->getData() != $form['user']['emailOld']->getData())){
                    $oldPassword = $form['user']['oldPassword']->getData();
                    $encoder = $this->container->get('security.encoder_factory')->getEncoder( $user );
                    $encodedOldPassword = $encoder->encodePassword( $oldPassword, $user->getSalt() );
                    if ( $encodedOldPassword != $baseUser->getPassword() ) {
                        $entity->getUser()->setEmail($form['user']['emailOld']->getData());
                    } else {
                        // $entity->getUser()->setPassword($encoder->encodePassword( $oldPassword, $user->getSalt()));
                        $entity->getUser()->setPlainPassword( $newPassword );
                        $changes[] = 'userPassword';
                    }
                }
                $userManager = $this->container->get('system.user_manager');
                $userManager->updateUser($user);
                $em->persist( $user );
                $em->flush();

                //Send email
                if (sizeof($changes) > 0){
                    $mailer = $this->container->get('mailer');
                    $router = $this->container->get('router');
                    $templating = $this->container->get('templating');

                    $email = $user->getEmail();
                    
                    //$em = $this->container->get('doctrine.orm.entity_manager');
                    
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Change password');
                        
                    $message->setFrom(array('bot@ssp-soft.by' => 'webmaster'));

                    $message->setTo($email)
                        ->setBody(
                            $templating->render('SystemUserBundle:Profile:editProfileSuccessEmail.html.twig', 
                            array(
                                'user' => $user,
                                'changes' => $changes)
                            ), 'text/html', 'utf-8'
                        );
                    
                    $mailer->send($message);
                }
                return new RedirectResponse($this->container->get('router')->generate('profile_edit'));     
                               

            }
        }    
       
        return array(
        	'form' => $form->createView(),
            'name' => $entity->getUserName()
        );
    }

   
}