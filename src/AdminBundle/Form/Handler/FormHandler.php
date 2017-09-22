<?php

namespace AdminBundle\Form\Handler;

use Symfony\Component\HttpFoundation\Request;

abstract class FormHandler
{

	protected $em;
    protected $request;
    protected $event_dispatcher;
    protected $securityContext;
    protected $object;
    protected $form;
    
    public function __construct($em,$securityContext,$event_dispatcher)
    {
        $this->em = $em;
        $this->securityContext = $securityContext;
        $this->event_dispatcher = $event_dispatcher;
    }

    public function handle( Request $request, $form )
    {
        $this->request = $request;
        $this->form = $form;

        $status = $this->preHandle();
        if ($status) {
            $this->postHandle();
            $this->finalize();
        }

        return $status;
    }

    protected function preHandle()
    {

        if ($this->request->getMethod() == 'POST') {

            $this->form->handleRequest($this->request);

            if ($this->form->isValid()) {
                $this->object = $this->form->getData();

                return true;
            } 
            
            return false;
        }

        return null;
    }

    abstract protected function postHandle();

    protected function finalize()
    {
        $this->em->persist($this->object);
        $this->em->flush();
    }

    public function getObject() 
    {
        return $this->object;
    }

}