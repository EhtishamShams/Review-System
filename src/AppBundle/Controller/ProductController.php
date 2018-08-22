<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 16/08/2018
 * Time: 5:08 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Product;
use AppBundle\Form\ProductFormType;
use AppBundle\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 * @Security("is_granted('ROLE_USER')")
 */
class ProductController extends Controller
{
    /**
     * @Route("/index", name="product_list")
     */
    public function indexAction(){


        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['user' => $this->getUser()]);

        if ($shop)
            $products = $this->getDoctrine()->getRepository('AppBundle:Product')->findAllProductsByShop($shop);
        else
            $products = null;

        return $this->render("product/index.html.twig", array(
            "products" => $products
        ));
    }

    /**
     * @Route("/new", name="product_new")
     */
    public function newAction(Request $request, FileUploader $fileUploader)
    {
        $form = $this->createForm(ProductFormType::class);
        $shop = $this->getDoctrine()->getRepository('AppBundle:Shop')->findOneBy(['user' => $this->getUser()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setShop($shop);

            $file = $product->getPicPath();

            if ($file != null) {
                $fileName = $fileUploader->upload($file);
                $product->setPicPath($fileName);
            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', sprintf('Product Created!'));

            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/new.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit")
     */
    public function editAction(Request $request, Product $product, FileUploader $fileUploader)
    {
        $shop = $product->getShop();

        if ($this->getUser() != $shop->getUser()){
            return $this->redirectToRoute('product_list');
        }
        $oldPicPath = $product->getPicPath();
        $product->setPicPath(null);

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $prod = $form->getData();

            $file = $prod->getPicPath();

            if ($file != null) {
                $fileName = $fileUploader->upload($file);
                $prod->setPicPath($fileName);
            } else {
                $prod->setPicPath($oldPicPath);
            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($prod);
            $em->flush();

            $this->addFlash('success', 'Product Updated!');

            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/edit.html.twig', [
            'productForm' => $form->createView(),
            'pic' => $oldPicPath
        ]);
    }

    /**
     * @Route("/{id}/delete", name="product_delete", methods={"POST"})
     */
    public function deleteAction(Request $request, Product $product)
    {
        $shop = $product->getShop();

        if ($this->getUser() != $shop->getUser()){
            return $this->redirectToRoute('product_list');
        }

        $reviews = $this->getDoctrine()->getRepository('AppBundle:Review')->findBy(['product' => $product]);

        if ($reviews != null){
            $this->addFlash('failure', 'You cannot delete any product with 1 or more reviews!');
            return $this->redirectToRoute('product_list');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Product Deleted!');

        return $this->redirectToRoute('product_list');
    }

    /**
     * @Route("/{id}/show", name="product_show")
     */
    public function showAction(Product $product)
    {
        $shop = $product->getShop();

        if ($this->getUser() != $shop->getUser()){
            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}