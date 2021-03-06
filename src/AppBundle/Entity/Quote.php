<?php
// src/AppBundle/Entity/Quote.php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuoteRepository")
 * @ORM\Table(name="quote")
 * @Serializer\ExclusionPolicy("all")
 */
class Quote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"list","details"})
     * @Serializer\Expose
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="quotes", cascade={"persist"})
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", onDelete="CASCADE")
     * @Serializer\MaxDepth(1)
     * @Serializer\Groups({"list","details","wp_front"})
     * @Serializer\Expose
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Serializer\Groups({"list","details","wp_front"})
     * @Serializer\Expose
     */
    private $content;


    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("authorId")
     * @Serializer\Groups({"list","details"})
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAuthorId() 
    {
        return $this->author ? $this->author->getId() : null;
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
     * Set content
     *
     * @param string $content
     *
     * @return Quote
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Set author
     *
     * @param \AppBundle\Entity\Author $author
     *
     * @return Quote
     */
    public function setAuthor(\AppBundle\Entity\Author $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \AppBundle\Entity\Author
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
