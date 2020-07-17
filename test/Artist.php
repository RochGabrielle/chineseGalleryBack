<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
* @ORM\Entity()
* @ORM\Table(name="artist")
* */
class Artist implements TranslatableInterface
{
    use TranslatableTrait;

    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
    * @ORM\Column(type="string")
    */
    protected $name;

     /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Discount", inversedBy="artists")
     */
    private $dynasty;

    /**
    * @ORM\Column(type="integer", name="birth_date")
    */
    protected $birth_date;

    /**
    * @ORM\Column(type="integer", name="death_date")
    */
    protected $death_date;

     public function getDynasty(): ?Dynasty
    {
        return $this->dynasty;
    }

    public function setDynasty(?Dynasty $dynasty): self
    {
        $this->dynasty = $dynasty;

        return $this;
    }
}