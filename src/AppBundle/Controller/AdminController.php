<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\EventDispatcher\GenericEvent;

class AdminController extends Controller
{

    /**
     * @Route("/admin/dashboard", name="_my_admin_dashboard")
     */
    public function dashboardAction(Request $request)
    {
        return $this->render('AdminBundle::dashboard.html.twig', [
        ]);
    }

}