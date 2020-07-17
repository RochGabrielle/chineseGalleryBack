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
    private $date_of_creation;

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
     * @ORM\ManyToMany(targetEntity=Size::class, inversedBy="articles")
     */
    private $Size;

    /**
     * @ORM\ManyToMany(targetEntity=SizeCategory::class, inversedBy="articles")
     */
    private $size_category;

    /**
     * @ORM\ManyToMany(targetEntity=Theme::class, inversedBy="articles")
     */
    private $theme;

    public function __construct()
    {
        $this->Size = new ArrayCollection();
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

    public function getDateOfCreation(): ?int
    {
        return $this->date_of_creation;
    }

    public function setDateOfCreation(?int $date_of_creation): self
    {
        $this->date_of_creation = $date_of_creation;

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
    public function getSize(): Collection
    {
        return $this->Size;
    }

    public function addSize(Size $size): self
    {
        if (!$this->Size->contains($size)) {
            $this->Size[] = $size;
        }

        return $this;
    }

    public function removeSize(Size $size): self
    {
        if ($this->Size->contains($size)) {
            $this->Size->removeElement($size);
        }

        return $this;
    }

    /**
     * @return Collection|SizeCategory[]
     */
    public function getSizeCategory(): Collection
    {
        return $this->size_category;
    }

    public function addSizeCategory(SizeCategory $sizeCategory): self
    {
        if (!$this->size_category->contains($sizeCategory)) {
            $this->size_category[] = $sizeCategory;
        }

        return $this;
    }

    public function removeSizeCategory(SizeCategory $sizeCategory): self
    {
        if ($this->size_category->contains($sizeCategory)) {
            $this->size_category->removeElement($sizeCategory);
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
}
