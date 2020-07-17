<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
* @ORM\Entity()
* @ORM\Table(name="category_translation")
* */
class CategoryTranslation implements TranslationInterface
{
    use TranslationTrait;
    
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    public $id;

    /**
    * @ORM\Column(type="string")
    */
    protected $category_name;

    public function getCategory_name(): string
    {
        return $this->category_name;
    }

    public function setCategory_name(string $category_name): void
    {
        $this->category_name = $category_name;
    }

}