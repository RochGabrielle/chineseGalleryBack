<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass=ArtistRepository::class)
 */
class Artist implements TranslatableInterface
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
    private $name;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $birth_date;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $death_date;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="artist")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity=Dynasty::class, inversedBy="artists")
     */
    private $dynasty;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->dynasty = new ArrayCollection();
    }

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

    public function getBirthDate(): ?int
    {
        return $this->birth_date;
    }

    public function setBirthDate(?int $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getDeathDate(): ?int
    {
        return $this->death_date;
    }

    public function setDeathDate(?int $death_date): self
    {
        $this->death_date = $death_date;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setArtist($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getArtist() === $this) {
                $article->setArtist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Dynasty[]
     */
    public function getDynasty(): Collection
    {
        return $this->dynasty;
    }

    public function addDynasty(Dynasty $dynasty): self
    {
        if (!$this->dynasty->contains($dynasty)) {
            $this->dynasty[] = $dynasty;
        }

        return $this;
    }

    public function removeDynasty(Dynasty $dynasty): self
    {
        if ($this->dynasty->contains($dynasty)) {
            $this->dynasty->removeElement($dynasty);
        }

        return $this;
    }
}