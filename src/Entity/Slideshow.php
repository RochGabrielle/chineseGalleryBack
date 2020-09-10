<?php

namespace App\Entity;

use App\Repository\SlideshowRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass=SlideshowRepository::class)
 */
class Slideshow implements TranslatableInterface
{
    use TranslatableTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $desktop;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tablet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mobile;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDesktop(): ?string
    {
        return $this->desktop;
    }

    public function setDesktop(?string $desktop): self
    {
        $this->desktop = $desktop;

        return $this;
    }

    public function getTablet(): ?string
    {
        return $this->tablet;
    }

    public function setTablet(?string $tablet): self
    {
        $this->tablet = $tablet;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
