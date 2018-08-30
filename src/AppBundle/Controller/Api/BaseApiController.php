<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 24/08/2018
 * Time: 4:13 AM
 */

namespace AppBundle\Controller\Api;


use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends Controller
{
    protected function createApiResponse($data, $statusCode = 200){
        $json = $this->serialize($data);

        return new Response($json, $statusCode, [
            'Content-Type' => 'application/json'
        ]);
    }

    protected function serialize($data){
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        return $this->get('jms_serializer')
            ->serialize($data, 'json', $context);
    }

    protected function processForm(Request $request, FormInterface $form){
        $body = $request->getContent();
        $data = json_decode($body, true);

        $form->submit($data);
    }
}