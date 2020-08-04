<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article implements TranslatableInterface
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
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $birth;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Artist::class, inversedBy="articles")
     */
    private $artist;

    /**
     * @ORM\ManyToOne(targetEntity=Discount::class, inversedBy="articles")
     */
    private $discount;

    /**
     * @ORM\ManyToOne(targetEntity=Dynasty::class, inversedBy="articles")
     */
    private $dynasty;

    /**
     * @ORM\ManyToOne(targetEntity=Material::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $material;

    /**
     * @ORM\OneToMany(targetEntity=Size::class, mappedBy="article")
     */
    private $sizes;

    /**
     * @ORM\ManyToMany(targetEntity=Theme::class, inversedBy="articles")
     */
    private $theme;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $smallpicturename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $bigpicturename;

    public function __construct()
    {
        $this->sizes = new ArrayCollection();
        $this->size_category = new ArrayCollection();
        $this->theme = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
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

    public function getBirth(): ?int
    {
        return $this->birth;
    }

    public function setBirth(?int $birth): self
    {
        $this->birth = $birth;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDynasty(): ?Dynasty
    {
        return $this->dynasty;
    }

    public function setDynasty(?Dynasty $dynasty): self
    {
        $this->dynasty = $dynasty;

        return $this;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): self
    {
        $this->material = $material;

        return $this;
    }

    /**
     * @return Collection|Size[]
     */
    public function getSizes(): Collection
    {
        return $this->sizes;
    }

    public function addSize(Size $size): self
    {
        if (!$this->sizes->contains($size)) {
            $this->sizes[] = $size;
        }

        return $this;
    }

    public function removeSize(Size $size): self
    {
        if ($this->sizes->contains($size)) {
            $this->sizes->removeElement($size);
        }

        return $this;
    }

    /**
     * @return Collection|Theme[]
     */
    public function getTheme(): Collection
    {
        return $this->theme;
    }

    public function addTheme(Theme $theme): self
    {
        if (!$this->theme->contains($theme)) {
            $this->theme[] = $theme;
        }

        return $this;
    }

    public function removeTheme(Theme $theme): self
    {
        if ($this->theme->contains($theme)) {
            $this->theme->removeElement($theme);
        }

        return $this;
    }

     public function getSmallpicturename(): ?string
    {
        return $this->smallpicturename;
    }

    public function setSmallpicturename(string $smallpicturename): self
    {
        $this->smallpicturename = $smallpicturename;

        return $this;
    }

     public function getBigpicturename(): ?string
    {
        return $this->bigpicturename;
    }

    public function setBigpicturename(string $bigpicturename): self
    {
        $this->bigpicturename = $bigpicturename;

        return $this;
    }
}
