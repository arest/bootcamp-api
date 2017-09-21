<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $helper = $this->get('security.authentication_utils');

        return $this->container->get('templating')->renderResponse('security/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ]);
    }
    
    public function loginCheckAction(Request $request)
    {
        
    }
}