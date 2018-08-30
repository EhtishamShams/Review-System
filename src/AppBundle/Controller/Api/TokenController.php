<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 30/08/2018
 * Time: 4:59 PM
 */

namespace AppBundle\Controller\Api;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TokenController extends BaseApiController
{
    /**
     * @Route("/api/tokens", methods={"POST"})
     */
    public function newTokenAction(Request $request){

//        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
//        $client = $clientManager->createClient();
//        $client->setAllowedGrantTypes(array('password'));
//        $clientManager->updateClient($client);

        $bodyArr = json_decode($request->getContent(), true);

        return $this->redirect($this->generateUrl('fos_oauth_server_token', array(
            'grant_type' => $bodyArr['grant_type'],
//            'grant_type' => 'password',
//            'grant_type' => $client->getAllowedGrantTypes(),
//            'client_id' => $client->getPublicId(),
            'client_id' => $bodyArr['client_id'],
            'client_secret' => $bodyArr['client_secret'],
            'username' => $bodyArr['username'],
            'password' => $bodyArr['password'],
//            'client_secret' => $client->getSecret(),
//            'response_type' => 'code'
        )));
    }
}