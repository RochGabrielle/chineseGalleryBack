<?php

namespace App\Entity;

use App\Repository\ThemeTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
 * @ORM\Entity(repositoryClass=ThemeTranslationRepository::class)
 */
class ThemeTranslation implements TranslationInterface
{
    use TranslationTrait;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $theme_name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getThemeName(): ?string
    {
        return $this->theme_name;
    }

    public function setThemeName(string $theme_name): self
    {
        $this->theme_name = $theme_name;

        return $this;
    }
}
