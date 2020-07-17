<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
* @ORM\Entity()
* @ORM\Table(name="dynasty")
* */
class Dynasty implements TranslatableInterface
{
    use TranslatableTrait;
    
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    public $id;

    /**
    * @ORM\Column(type="string")
    */
    public $dynasty_name;

    /**
    * @ORM\Column(type="integer")
    */
    public $date_beginning;

    /**
    * @ORM\Column(type="integer")
    */
    public $date_end;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Article", mappedBy="dynasty")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Artist", mappedBy="dynasty")
     */
    private $artists;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->artists = new ArrayCollection();
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

}