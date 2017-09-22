<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Controller\TokenAuthenticatedController;

class DashboardController extends Controller implements TokenAuthenticatedController
{

    /**
     * @Route("/dashboard", name="_app_admin_dashboard")
     */
    public function dashboardAction(Request $request)
    {
        return $this->render('AdminBundle::dashboard.html.twig');
    }

}