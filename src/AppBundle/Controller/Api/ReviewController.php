<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 24/08/2018
 * Time: 5:16 AM
 */

namespace AppBundle\Controller\Api;


use AppBundle\Entity\Review;
use AppBundle\Form\ReviewFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ReviewController extends BaseApiController
{
    /**
     * @Route("/api/reviews/{id}", name="api_reviews_show", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function listAction($id){

        $product = $this->getDoctrine()->getRepository('AppBundle:Product')
            ->findOneBy(['id' => $id]);

        if (!$product){
            throw $this->createNotFoundException('Product not found!');
        }

        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['user' => $this->getUser()]);

        $prodShop = $product->getShop();

//        if ($this->getUser() != $shop->getUser()){
        if ($prodShop != $shop){
            throw $this->createAccessDeniedException('Access Denied!');
        }

        $reviews = $product->getReviews();

        $data = ['reviews' => $reviews];
        $response = $this->createApiResponse($data);

        return $response;

    }

    /**
     * @Route("/api/reviews/{id}", name="api_reviews_new", methods={"POST"})
     */
    public function newAction($id, Request $request){
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')
            ->findOneBy(['id' => $id]);

        if (!$product){
            throw $this->createNotFoundException('Product not found!');
        }

//        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['id' => 39]);

//        $prodShop = $product->getShop();

//        if ($this->getUser() != $shop->getUser()){
//        if ($prodShop != $shop){
//            throw $this->createAccessDeniedException('Access Denied!');
//        }

        $review = new Review();
        $form = $this->createForm(ReviewFormType::class, $review);
        $review->setProduct($product);

        $this->processForm($request, $form);

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
        }

        $response = $this->createApiResponse($review, 201);

        return $response;
    }
}