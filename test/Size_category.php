<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="size_category")
* */
class Size_category
{
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    public $id;

    /**
    * @ORM\Column(type="string")
    */
    public $categoryName;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Article", mappedBy="size_category")
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