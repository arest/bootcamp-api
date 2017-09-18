<?php
namespace AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class CRUDEvent extends Event
{
	protected $object;

	function __construct($object)
	{
		$this->object = $object;
	}

	public function getObject()
	{
		return $this->object;
	}
}