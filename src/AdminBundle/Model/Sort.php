<?php

namespace AdminBundle\Model;

class Sort
{
	public $field;
	public $direction;

	public function __construct(array $params)
	{
		if (isset($params['sort'])) {
			$this->field = $params['sort'];
		}
		if (isset($params['direction'])) {
			$this->direction = $params['direction'];
		}
	}
}