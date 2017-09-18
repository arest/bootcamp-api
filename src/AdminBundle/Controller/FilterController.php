<?php
namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\EventListener\FilterBag;

class FilterController
{
	private $templating;
	private $filter_bag;

    public function __construct(EngineInterface $templating, FilterBag $filter_bag)
    {
        $this->templating = $templating;
        $this->filter_bag = $filter_bag;
    }

    public function searchAction()
    {
		$filters = $this->get('app.filter.bag')->get('filters');
        $form = $this->get('form.factory')->createNamed( 'filters','filters', $filters );

    	return $this->templating->renderResponse(
    		'admin/filters/search.html.twig',
    		array(
            	'form' => $form->createView(),
    		)
    	);
    }
}