<?php

namespace App\Entity;

use App\Repository\MuseumRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MuseumRepository::class)
 */
class Museum
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\OneToOne(targetEntity=Article::class, inversedBy="museum", cascade={"persist", "remove"})
     */
    private $OneToOne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getOneToOne(): ?Article
    {
        return $this->OneToOne;
    }

    public function setOneToOne(?Article $OneToOne): self
    {
        $this->OneToOne = $OneToOne;

        return $this;
    }
}
