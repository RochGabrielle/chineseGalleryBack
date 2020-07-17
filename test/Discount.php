<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="discount")
* */
class Discount
{
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    public $id;

    /**
    * @ORM\Column(type="float")
    */
    public $discount_value;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Article", mappedBy="discount")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

}