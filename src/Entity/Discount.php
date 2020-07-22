<?php

namespace App\Entity;

use App\Repository\DiscountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountRepository::class)
 */
class Discount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount_value;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $discount_name;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="discount")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscountValue(): ?float
    {
        return $this->discount_value;
    }

    public function setDiscountValue(?float $discount_value): self
    {
        $this->discount_value = $discount_value;

        return $this;
    }

    public function getDiscountName(): ?float
    {
        return $this->discount_name;
    }

    public function setDiscountName(?float $discount_name): self
    {
        $this->discount_name = $discount_name;

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
            $article->setDiscount($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getDiscount() === $this) {
                $article->setDiscount(null);
            }
        }

        return $this;
    }
}
