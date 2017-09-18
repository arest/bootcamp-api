<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AdminBundle\Controller\TokenAuthenticatedController;
use Doctrine\Common\Util\Inflector;
use AdminBundle\Repository\CRUDDecorator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use AdminBundle\Model\Sort;
use AdminBundle\Controller\Export\BaseExport;

abstract class CRUDBaseController 
{
    protected $model_type;
    protected $repository;
    protected $form_type;
    protected $form_handler = 'app.form.crud.handler';
    protected $templating;
    protected $form_factory;
    protected $filter_bag;
    protected $doctrine;
    protected $request;
    protected $router;
    protected $route_pattern;
    protected $templates;
    protected $list_fields = array('id','name');
    protected $list_actions = [];
    protected $export_fields;
    protected $em;
    protected $session;
    protected $security_context;
    protected $translator;
    protected $exporter;
    protected $authorization_checker;
    protected $search_fields = array();
    protected $event_dispatcher;

    public function __construct(EngineInterface $templating, $form_factory, $doctrine, $router, $session, $security_context, $translator, $filter_bag, $authorization_checker, $event_dispatcher )
    {
        $this->templating = $templating;
        $this->form_factory = $form_factory;
        $this->doctrine = $doctrine;
        $this->router = $router;
        $this->session = $session;
        $this->security_context = $security_context;
        $this->translator = $translator;
        $this->filter_bag = $filter_bag;
        $this->authorization_checker = $authorization_checker;
        $this->event_dispatcher = $event_dispatcher;
    }

    protected function getFormFactory()
    {
        return $this->form_factory;
    }

    public function setModelType( $model_type )
    {
        $this->route_pattern = Inflector::tableize($this->getClassName($model_type));
        $this->model_type = $model_type;
    }

    public function setRoutePattern( $route_pattern )
    {
        $this->route_pattern = $route_pattern;
    }

    protected function getRepository()
    {
        return $this->repository;
    }

    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    public function setFormType( $form_type )
    {
        $this->form_type = $form_type;
    }

    public function setHandler( $form_handler )
    {
        $this->form_handler = $form_handler;
    }

    public function setTemplates( $templates )
    {
        $this->templates = $templates;
    }

    public function setListFields( $fields )
    {
        $this->list_fields = $fields;
    }

    public function setListActions( $fields )
    {
        $this->list_actions = $fields;
    }

    public function setSearchFields( $fields )
    {
        $this->search_fields = $fields;
    }

    public function setExportFields( array $fields )
    {
        $this->export_fields = $fields;
    }

    public function setExportHelper( BaseExport $exporter )
    {
        $this->exporter = $exporter;
    }

    abstract public function createAction(Request $request);
    abstract public function editAction(Request $request, $id );
    abstract public function listAction(Request $request);
    abstract public function removeAction(Request $request, $id);
    abstract public function exportAction(Request $request);


    protected function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->templating->renderResponse($view, $parameters, $response);
    }

    protected function redirect($url) {
        return new RedirectResponse($url);
    }

    protected function getFormView() 
    {
        $form_view = 'AdminBundle:crud:form.html.twig';
        if (isset($this->templates['form'])) {
            $form_view = $this->templates['form'];
        }
        return $form_view;
    }

    protected function getEditView() 
    {
        $view = 'AdminBundle:crud:edit.html.twig';
        if (isset($this->templates['edit'])) {
            $view = $this->templates['edit'];
        }
        return $view;
    }

    protected function generateUrl( $route, $parameters=array(),$absolute=false)
    {
        $route = str_replace('%s', $this->route_pattern, $route );
        return $this->router->generate( $route, $parameters, $absolute );
    }

    public function getClassName($my_class) {
        $path = explode('\\', $my_class);
        return array_pop($path);
    }

    protected function addFlash($type='notice',$value='admin.crud.flash.success')
    {
        $this->session->getFlashBag()->add($type, $value);
    }

    protected function getCurrentUser()
    {
        return $this->security_context->getToken()->getUser();
    }

    protected function checkRoutePermission($request)
    {
        return $this->denyAccessUnlessGranted('view_route', $request->get('_route'), 'You cannot access this route' );
    }

    protected function denyAccessUnlessGranted( $role, $item, $message = 'You cannot edit this item.' ) 
    {
        if (false === $this->authorization_checker->isGranted( $role, $item)) {
            throw  new AccessDeniedHttpException($message);
        }
    }

    protected function getRequest()
    {
        return $this->request;
    }

    protected function getMetadata() 
    {
        $metadata = $this->doctrine->getClassMetadata( $this->model_type );
        return $metadata->fieldMappings;
    }

    protected function getEventDispatcher()
    {
        return $this->event_dispatcher;
    }
}