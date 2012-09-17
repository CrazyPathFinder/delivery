<?php
namespace Delivery\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Browse controller.
 *
 * @Route("/orders")
 */
class OrdersController extends Controller
{
	/**
     * Main System Page
     *
     * @Route("/", name="backoffice_orders")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('SystemUserBundle:UserProfile')->find( $user->getId());
        
        if(!$entity || !is_object($entity))
          throw  $this->createNotFoundException('User doesn\'t have profile');

        return array('name' => $entity->getUserName());
    }
}