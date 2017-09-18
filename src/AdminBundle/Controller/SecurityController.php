<?php

namespace AdminBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseSecurityController
{

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        $template = 'AdminBundle:security:login.html.twig';

        return $this->container->get('templating')->renderResponse($template, $data);
    }

    /**
     * @Route("/login", name="_app_admin_login")
     */
    public function loginAction(Request $request)
    {
        return parent::loginAction($request);
    }

    /**
     * @Route("/logout", name="_app_admin_logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/login_check", name="_app_admin_login_check")
     */
    public function loginCheckAction(Request $request)
    {
        return parent::loginAction($request);
    }

}