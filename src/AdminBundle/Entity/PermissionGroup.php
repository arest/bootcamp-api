<?php
// src/AdminBundle/Entity/PermissionGroup.php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="simple_admin_permission_group")
 */
class PermissionGroup
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Permission", mappedBy="groups", cascade={"persist"})
     */
    protected $permissions;    

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permissions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name ? $this->name : 'New';
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return PermissionGroup
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add permission
     *
     * @param \AdminBundle\Entity\Permission $permission
     *
     * @return PermissionGroup
     */
    public function addPermission(\AdminBundle\Entity\Permission $permission)
    {
        $permission->addGroup($this);
        $this->permissions[] = $permission;

        return $this;
    }

    /**
     * Remove permission
     *
     * @param \AdminBundle\Entity\Permission $permission
     */
    public function removePermission(\AdminBundle\Entity\Permission $permission)
    {
        $this->permissions->removeElement($permission);
    }

    /**
     * Get permissions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return PermissionGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
