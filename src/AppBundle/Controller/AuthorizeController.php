<?php
namespace AppBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use FOS\OAuthServerBundle\Controller\AuthorizeController as BaseAuthorizeController;

use AppBundle\Form\Type\AuthorizeFormType;
use AppBundle\Form\Model\Authorize;
use AppBundle\Entity\Client;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorizeController extends BaseAuthorizeController
{
    public function authorizeAction(Request $request)
    {
        if (!$request->get('client_id')) {
            throw new NotFoundHttpException("Client id parameter {$request->get('client_id')} is missing.");
        }
        
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->findClientByPublicId($request->get('client_id'));
        
        if (!($client instanceof Client)) {
            throw new NotFoundHttpException("Client {$request->get('client_id')} is not found.");
        }
        
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        
        $authorize = new Authorize();

        $form = $this->container->get('form.factory')->createNamed( null, AuthorizeFormType::class, $authorize, array('csrf_protection' => false) );

        $formHandler = $this->container->get('app.oauth_server.authorize.form_handler');
        
        
        if (($response = $formHandler->process($authorize, $form)) !== false) {
            return $response;
        }

        return $this->container->get('templating')->renderResponse('authorize/authorize.html.twig', [
            'form' => $form->createView(),
            'client' => $client,
        ]);
    }
}