<?php

namespace AppBundle\Controller;

// use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\Request;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;


use AppBundle\Form\Type\QuoteFormType;
use AppBundle\Entity\Quote;


/**
 * @Route("/api/quote")
 */
class QuoteController extends ApiController
{

    /**
     * Get random quote
     *
     * @Method("GET")
     * @Route("/random", name="_api_quote_random", options={"expose"=true})
     * @View(serializerGroups={"details"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns a random quote",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Quote::class, groups={"details"})
     *     )
     * )
     * @SWG\Tag(name="random")
     * )
     */
    public function getRandomAction()
    {
        $results = $this->get('app.repository.quote')->getRandom();
        return $this->view( $results, 200 );
    }

    /**
     * Get quote list
     *
     * @Method("GET")
     * @Route("/list", name="_api_quote_list", options={"expose"=true})
     * @View(serializerGroups={"list"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the quote list",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Quote::class, groups={"list"})
     *     )
     * )
     * @SWG\Tag(name="list")
     * )
     */
    public function listAction()
    {
        $results = $this->get('app.repository.quote')->getAll();
        return $this->view( $results, 200 );
    }

    /**
     * Get quote details
     *
     * @Method("GET")
     * @Route("/{id}", name="_api_quote_get", options={"expose"=true})
     * @View(serializerGroups={"details"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns a quote details",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Quote::class, groups={"details"})
     *     )
     * )
     * @SWG\Tag(name="list")
     */
    public function getAction($id)
    {   
        $item = $this->get('app.repository.quote')->find( $id );

        return $this->view( $item, 200 );
    }



    /**
     * Create a new quote
     * @Method("POST")
     * @View(serializerGroups={"details"})
     * @Route("", name="_api_quote_create", options={"expose"=true})
     * @RequestParam(name="author_id", description="Author ID", strict=false)
     * @RequestParam(name="content", description="Content", strict=false)
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $quote = new Quote();
        $form = $this->get('form.factory')->createNamed( null, QuoteFormType::class, $quote, array('csrf_protection' => false) );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($quote);
            $em->flush();
            return $this->view($quote, 201);
        }

        return $this->view( $this->handleForm($form), 400);
    }

}

