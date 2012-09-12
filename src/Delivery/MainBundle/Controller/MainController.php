<?php
namespace Delivery\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Main controller.
 *
 * @Route("/backoffice")
 */
class MainController extends Controller
{
	/**
     * Main System Page
     *
     * @Route("/main", name="backoffice_main_page")
     * @Template()
     */
    public function mainAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('SystemUserBundle:UserProfile')->find( $user->getId());
        
        if(!$entity || !is_object($entity))
          throw  $this->createNotFoundException('User doesn\'t have profile');

        return array('name' => $entity->getUserName());
    }
}