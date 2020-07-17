<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
* @ORM\Entity()
* @ORM\Table(name="dynasty_translation")
* */
class DynastyTranslation implements TranslationInterface
{
    use TranslationTrait;
    
    /**
    * @ORM\Column(type="text")
    */
    protected $description;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}