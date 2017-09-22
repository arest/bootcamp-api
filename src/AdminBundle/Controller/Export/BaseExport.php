<?php

namespace AdminBundle\Controller\Export;


abstract class BaseExport
{
	public function __construct($translator)
	{
		$this->translator = $translator;
	}

	abstract public function handleRow(array $fields, array $row);
	abstract public function getQuery();

}