<?php

namespace AdminBundle\Entity;

trait fieldsFromArray {
	
    public function fromArray(array $userInput)
    {
        foreach ($userInput as $key => $value) {
            $this->$key = $value;
        }
    }
}