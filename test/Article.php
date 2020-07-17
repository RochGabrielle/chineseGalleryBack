<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
* @ORM\Entity()
* @ORM\Table(name="article")
* */
class Article implements TranslatableInterface
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
    public $reference;

    /**
    * @ORM\Column(type="string")
    */
    public $title;

    /**
    * @ORM\Column(type="text")
    */
    public $content;

    /**
    * @ORM\Column(type="integer", name="date")
    */
    public $date_of_creation;

    /**
    * @ORM\Column(type="float")
    */
    public $price;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="articles")
     */
    private $category;

   
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Size_category", inversedBy="articles")
     */
    private $size_category;

    

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Dynasty", inversedBy="articles")
     */
    private $dynasty;

   

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Material", inversedBy="articles")
     */
    private $material;

   

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Theme", inversedBy="articles")
     */
    private $theme;

     /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Discount", inversedBy="articles")
     */
    private $discount;

    
     public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

     public function getDynasty(): ?Dynasty
    {
        return $this->dynasty;
    }

    public function setDynasty(?Dynasty $dynasty): self
    {
        $this->dynasty = $dynasty;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $Theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getSize_category(): ?Size_category
    {
        return $this->size_category;
    }

    public function setSize_category(?Size_category $size_category): self
    {
        $this->size_category = $size_category;

        return $this;
    }

     public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $Material): self
    {
        $this->material = $material;

        return $this;
    }

     public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

}