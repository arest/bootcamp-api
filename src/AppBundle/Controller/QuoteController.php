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
     * @View(serializerGroups={"wp_front"}, serializerEnableMaxDepthChecks=true)
     * @SWG\Response(
     *     response=200,
     *     description="Returns a random quote",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Quote::class, groups={"wp_front"})
     *     )
     * )
     * @SWG\Tag(name="quote")
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
     * @Route("", name="_api_quote_list", options={"expose"=true})
     * @View(serializerGroups={"list"}, serializerEnableMaxDepthChecks=true)
     * @SWG\Response(
     *     response=200,
     *     description="Returns the quote list",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Quote::class, groups={"list"})
     *     )
     * )
     * @SWG\Tag(name="quote")
     * )
     */
    public function listAction(Request $request)
    {
        $results = $this->get('app.repository.quote')->getAll( $request->query->all() );
        $total = $this->get('app.repository.quote')->getTotal();

        return $this->list( $results, $total );
    }

    /**
     * Get quote details
     *
     * @Method("GET")
     * @Route("/{id}", name="_api_quote_get", options={"expose"=true})
     * @View(serializerGroups={"details"}, serializerEnableMaxDepthChecks=true)
     * @SWG\Response(
     *     response=200,
     *     description="Returns a quote details",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Quote::class, groups={"details"})
     *     )
     * )
     * @SWG\Tag(name="quote")
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
     * @SWG\Response(
     *     response=201,
     *     description="Create new quote",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Quote::class, groups={"details"})
     *     )
     * )
     * @SWG\Tag(name="quote")
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


    /**
     * Update quote
     * @Method("PUT")
     * @View(serializerGroups={"details"})
     * @Route("/{id}", name="_api_quote_update", options={"expose"=true})
     * @RequestParam(name="author", description="Buy Now", strict=false)
     * @RequestParam(name="content", description="Auction", strict=false)
     * @SWG\Response(
     *     response=202,
     *     description="Update quote",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Quote::class, groups={"details"})
     *     )
     * )
     * @SWG\Tag(name="quote")
     */
    public function updateAction(Quote $quote, Request $request)
    {
        $form = $this->get('form.factory')->createNamed( null, QuoteFormType::class, $quote, [
            'method' => 'PUT',
            'csrf_protection' => false,
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quote);
            $em->flush();
            return $this->view($quote, 202);

        }

        return $this->view( $this->handleForm($form), 400);

    }

    /**
     * Remove quote
     * @Method("DELETE")
     * @Route("/{id}", name="_api_quote_delete", options={"expose"=true})
     * @RequestParam(name="id", description="Quote id", strict=true)
     * @View(serializerGroups={"details"},serializerEnableMaxDepthChecks=true)
     * @SWG\Response(
     *     response=202,
     *     description="Delete quote",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Quote::class, groups={"details"})
     *     )
     * )
     * @SWG\Tag(name="quote")
     */
    public function deleteAction(Quote $quote)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($quote);
        $em->flush();
        
        return $this->view( $quote, 202 );
    }

}

