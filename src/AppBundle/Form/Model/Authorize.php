<?php
namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Authorize
{

    /**
     * @Assert\IsTrue(
     *     message = "Please check the checkbox to allow access to your profile."
     * )
     *
     */
    protected $allowAccess;
    
    public function getAllowAccess()
    {
        return $this->allowAccess;
    }

    public function setAllowAccess($allowAccess)
    {
        $this->allowAccess = $allowAccess;
    }
}