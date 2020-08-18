<?php

namespace App\Entity;

use App\Repository\MuseumRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass=MuseumRepository::class)
 */
class Museum implements TranslatableInterface
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
    private $placeholder;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkname;

    /**
     * @ORM\OneToOne(targetEntity=Article::class, inversedBy="museum", cascade={"persist", "remove"})
     */
    private $OneToOne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

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

    public function getLinkname(): ?string
    {
        return $this->linkname;
    }

    public function setLinkname(?string $linkname): self
    {
        $this->linkname = $linkname;

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
