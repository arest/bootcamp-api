<?php

namespace AdminBundle\Controller\Export;

class SimpleExport extends BaseExport
{
	public function handleRow( array $fields, array $row) 
	{
		return $row;
	}

	public function getQuery()
	{
		return "SELECT * FROM page";
	}
}