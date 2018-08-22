<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 20/08/2018
 * Time: 7:06 AM
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Product;
use AppBundle\Form\ReviewFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/review")
 */
class ReviewController extends Controller
{
    /**
     * @Route("/", name="review_main")
     * @Security("is_granted('ROLE_USER')")
     */
    public function mainAction(){

        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['user' => $this->getUser()]);

        if ($shop)
            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAllProductsByShop($shop);
        else
            $products = null;

        return $this->render("review/main.html.twig", array(
            "products" => $products
        ));
    }

    /**
     * @Route("/{id}/index", name="review_list")
     * @Security("is_granted('ROLE_USER')")
     */
    public function indexAction(Product $product){

        $shop = $product->getShop();

        if ($this->getUser() != $shop->getUser()){
            return $this->redirectToRoute('product_list');
        }

        $reviews = $product->getReviews();

        return $this->render('review/index.html.twig', [
            'reviews' => $reviews
        ]);
    }

    /**
     * @Route("/{id}/new", name="review_new")
     */
    public function newAction(Request $request, Product $product){

        $form = $this->createForm(ReviewFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();
            $review->setProduct($product);

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            $this->addFlash('success', 'Review Added!');

            return $this->redirectToRoute('review_thanks');
        }

        return $this->render('review/new.html.twig', [
            'reviewForm' => $form->createView(),
            'product' => $product
        ]);
    }

    /**
     * @Route("/thanks", name="review_thanks")
     */
    public function thanksAction(){
        return $this->render('review/thanks.html.twig');
    }
}