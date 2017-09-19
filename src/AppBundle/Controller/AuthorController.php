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


use AppBundle\Form\Type\AuthorFormType;
use AppBundle\Entity\Author;


/**
 * @Route("/api/author")
 */
class AuthorController extends ApiController
{


    /**
     * Get author list
     *
     * @Method("GET")
     * @Route("/list", name="_api_author_list", options={"expose"=true})
     * @View(serializerGroups={"list"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns the author list",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Author::class, groups={"list"})
     *     )
     * )
     * @SWG\Tag(name="list")
     * )
     */
    public function listAction()
    {
        $results = $this->get('app.repository.author')->findAll();
        return $this->view( $results, 200 );
    }

    /**
     * Get author details
     *
     * @Method("GET")
     * @Route("/{id}", name="_api_author_get", options={"expose"=true})
     * @View(serializerGroups={"details"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns a author details",
     *     @SWG\Schema(
     *         type="object",
     *         @Model(type=Author::class, groups={"details"})
     *     )
     * )
     * @SWG\Tag(name="list")
     */
    public function getAction($id)
    {   
        $item = $this->get('app.repository.author')->find( $id );

        return $this->view( $item, 200 );
    }



    /**
     * Create a new author
     *
     * @Method("POST")
     * @View(serializerGroups={"details"})
     * @Route("", name="_api_author_create", options={"expose"=true})
     * @RequestParam(name="firstName", description="First Name", strict=false)
     * @RequestParam(name="lastName", description="Last Name", strict=false)
     * @RequestParam(name="email", description="Email", strict=false)
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $author = new Author();
        $form = $this->get('form.factory')->createNamed( null, AuthorFormType::class, $author, array('csrf_protection' => false) );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($author);
            $em->flush();
            return $this->view($author, 201);
        }

        return $this->view( $this->handleForm($form), 400);
    }


    /**
     * Update author
     * @Method("PUT")
     * @View(serializerGroups={"details"})
     * @Route("/{id}", name="_api_author_update", options={"expose"=true})
     * @RequestParam(name="firstName", description="First Name", strict=false)
     * @RequestParam(name="lastName", description="Last Name", strict=false)
     * @RequestParam(name="email", description="Email", strict=false)
     */
    public function updateAction(Author $author, Request $request)
    {
        $form = $this->get('form.factory')->createNamed( null, AuthorFormType::class, $author, [
            'method' => 'PUT',
            'csrf_protection' => false,
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->view($author, 202);

        }

        return $this->view( $this->handleForm($form), 400);

    }

    /**
     * Remove author
     * @Method("DELETE")
     * @Route("/{id}", name="_api_author_delete", options={"expose"=true})
     * @RequestParam(name="id", description="Author id", strict=true)
     * @View(serializerGroups={"details"},serializerEnableMaxDepthChecks=true)
     */
    public function deleteAction(Author $author)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($author);
        $em->flush();
        
        return $this->view( $author, 202 );
    }

}

