<?php
// src/AppBundle/Entity/Author.php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="author")
 * @UniqueEntity("email")
 * @Serializer\ExclusionPolicy("all")
 */
class Author
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
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     * @Serializer\Groups({"list","details"})
     * @Serializer\Expose
     * @Serializer\SerializedName("firstName")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     * @Serializer\Groups({"list","details"})
     * @Serializer\Expose
     * @Serializer\SerializedName("lastName")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Serializer\Groups({"list","details"})
     * @Serializer\Expose
     */
    private $email;


    /**
     * @ORM\OneToMany(
     *   targetEntity="AppBundle\Entity\Quote",
     *   mappedBy="author",
     *   cascade={"persist", "remove"}
     * )
     * @Serializer\Groups({"list","details"})
     * @Serializer\Expose
     */
    private $quotes;

}