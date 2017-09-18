<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToMany(
     *   targetEntity="AdminBundle\Entity\PermissionGroup",
     *   cascade={"persist"}
     * )
     */
    private $permissionGroups;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add permissionGroup
     *
     * @param \AdminBundle\Entity\PermissionGroup $permissionGroup
     *
     * @return User
     */
    public function addPermissionGroup(\AdminBundle\Entity\PermissionGroup $permissionGroup)
    {
        $this->permissionGroups[] = $permissionGroup;

        return $this;
    }

    /**
     * Remove permissionGroup
     *
     * @param \AdminBundle\Entity\PermissionGroup $permissionGroup
     */
    public function removePermissionGroup(\AdminBundle\Entity\PermissionGroup $permissionGroup)
    {
        $this->permissionGroups->removeElement($permissionGroup);
    }

    /**
     * Get permissionGroups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissionGroups()
    {
        return $this->permissionGroups;
    }
}
