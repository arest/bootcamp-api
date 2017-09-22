<?php

namespace AdminBundle\Model;

class Filters
{
	public $search;

	public function __construct(array $params)
	{
		if (isset($params['search'])) {
			$this->search = $params['search'];
		}
	}
}