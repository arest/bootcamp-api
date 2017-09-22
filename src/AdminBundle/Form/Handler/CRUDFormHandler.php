<?php

namespace AdminBundle\Form\Handler;
use AdminBundle\Event\CRUDEvent;
use AdminBundle\CRUDEvents;

class CRUDFormHandler extends FormHandler
{
    protected function postHandle()
    {
		$event = new CRUDEvent($this->object);
		$this->event_dispatcher->dispatch(CRUDEvents::POST_SUBMIT, $event);
    }
}