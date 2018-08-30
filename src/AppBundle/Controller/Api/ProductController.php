<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 24/08/2018
 * Time: 3:57 AM
 */

namespace AppBundle\Controller\Api;


use AppBundle\Entity\Product;
use AppBundle\Form\ProductFormType;
use AppBundle\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 */
class ProductController extends BaseApiController
{
    /**
     * @Route("/api/products", name="api_products_new", methods={"POST"})
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product);

        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['user' => $this->getUser()]);

//        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['id' => 39]);

        $product->setShop($shop);

        $this->processForm($request, $form);

        if ($form->isSubmitted()) {

            $product->setPicPath(null);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
        }

        $response = $this->createApiResponse($product, 201);

        $response->headers->set('Location', $this->generateUrl("api_products_show", [
            'id' => $product->getId()
        ]));

        return $response;
    }

    /**
     * @Route("/api/products/{id}", name="api_products_show", methods={"GET"})
     */
    public function showAction($id){

        $product = $this->getDoctrine()->getRepository('AppBundle:Product')
            ->findOneBy(['id' => $id]);

        if (!$product){
            throw $this->createNotFoundException('Product not found!');
        }

        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['user' => $this->getUser()]);

//        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['id' => 39]);

        $prodShop = $product->getShop();

//        if ($this->getUser() != $shop->getUser()){
        if ($prodShop != $shop){
            throw $this->createAccessDeniedException('Access Denied!');
        }



        $response = $this->createApiResponse($product);

        return $response;
    }

}