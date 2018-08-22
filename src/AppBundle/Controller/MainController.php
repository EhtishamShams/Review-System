<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 16/08/2018
 * Time: 4:04 AM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Security("is_granted('ROLE_USER')")
     */
    public function indexAction(AuthorizationCheckerInterface $authorizationChecker)
    {
        if ($authorizationChecker->isGranted('ROLE_ADMIN')){
            return $this->render('admin/index.html.twig');
        }

        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['user' => $this->getUser()]);

        if ($shop)
            $reviews = $this->getDoctrine()->getRepository('AppBundle:Review')->findTopReviews($shop);
        else
            $reviews = null;

        return $this->render('main/homepage.html.twig', [
            'reviews' => $reviews
        ]);
    }
}