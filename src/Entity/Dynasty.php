<?php

namespace App\Entity;

use App\Repository\DynastyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass=DynastyRepository::class)
 */
class Dynasty implements TranslatableInterface
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
    private $dynasty_name;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $date_beginning;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $date_end;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="dynasty")
     */
    private $articles;

    /**
     * @ORM\ManyToMany(targetEntity=Artist::class, mappedBy="dynasty")
     */
    private $artists;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->artists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDynastyName(): ?string
    {
        return $this->dynasty_name;
    }

    public function setDynastyName(string $dynasty_name): self
    {
        $this->dynasty_name = $dynasty_name;

        return $this;
    }

    public function getDateBeginning(): ?int
    {
        return $this->date_beginning;
    }

    public function setDateBeginning(?int $date_beginning): self
    {
        $this->date_beginning = $date_beginning;

        return $this;
    }

    public function getDateEnd(): ?int
    {
        return $this->date_end;
    }

    public function setDateEnd(?int $date_end): self
    {
        $this->date_end = $date_end;

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
            $article->setDynasty($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getDynasty() === $this) {
                $article->setDynasty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Artist[]
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists[] = $artist;
            $artist->addDynasty($this);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        if ($this->artists->contains($artist)) {
            $this->artists->removeElement($artist);
            $artist->removeDynasty($this);
        }

        return $this;
    }
}
