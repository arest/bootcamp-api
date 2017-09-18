<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Quote;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @Route("/api/quote")
 */
class ApiController extends FOSRestController
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

}

