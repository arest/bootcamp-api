<?php
namespace AdminBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;

use AdminBundle\Model\Filters;
use AdminBundle\Model\Sort;


class FilterListener
{  
    protected $filter_bag;
    protected $per_page;

    public function __construct( $filter_bag, $per_page )
    {
        $this->filter_bag = $filter_bag;
        $this->per_page = $per_page;
    }

    public function onKernelRequest(Event $event)
    {
        $request = $event->getRequest();
        $filters = $request->query->get('filters',array());
        $filters2['search'] = $request->query->get('q', null );

        $filters = array_merge( $filters2, $filters );
        $filters = new Filters($filters);
        $sort = new Sort($request->query->all());
        $page = $request->query->get('page',1);
        $per_page = $request->query->get('per_page', $this->per_page );
        $this->filter_bag->set('filters',$filters);
        $this->filter_bag->set('sort',$sort);
        $this->filter_bag->set('page',$page);
        $this->filter_bag->set('per_page',$per_page);

        if (!$request->hasPreviousSession()) {
            return;
        }
    }

}