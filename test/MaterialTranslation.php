<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
* @ORM\Entity()
* @ORM\Table(name="material_translation")
* */
class MaterialTranslation implements TranslationInterface
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
    protected $material_name;

    public function getMaterial_name(): string
    {
        return $this->material_name;
    }

    public function setMaterial_name(string $material_name): void
    {
        $this->material_name = $material_name;
    }

}