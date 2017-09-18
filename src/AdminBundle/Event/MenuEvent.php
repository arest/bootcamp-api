<?php
namespace AdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class MenuEvent extends Event
{
	protected $menu;

	function __construct($menu)
	{
		$this->menu = $menu;
	}

	public function getMenu()
	{
		return $this->menu;
	}
}