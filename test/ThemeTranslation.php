<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
* @ORM\Entity()
* @ORM\Table(name="theme_translation")
* */
class ThemeTranslation implements TranslationInterface
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
    protected $theme_name;

    public function getTheme_name(): string
    {
        return $this->theme_name;
    }

    public function setTheme_name(string $theme_name): void
    {
        $this->theme_name = $theme_name;
    }

}