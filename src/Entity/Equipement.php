<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\EquipementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipementRepository::class)
 */
class Equipement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_eq;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $desc_eq;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix_eq;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite_eq;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image_eq;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="equipements")
     */
    private $category_id;

    /**
     * @ORM\OneToMany(targetEntity=Images::class, mappedBy="equipements",orphanRemoval=true , cascade={"persist"})
     */
    private $images;
    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEq(): ?string
    {
        return $this->nom_eq;
    }

    public function setNomEq(string $nom_eq): self
    {
        $this->nom_eq = $nom_eq;

        return $this;
    }

    public function getDescEq(): ?string
    {
        return $this->desc_eq;
    }

    public function setDescEq(string $desc_eq): self
    {
        $this->desc_eq = $desc_eq;

        return $this;
    }

    public function getPrixEq(): ?int
    {
        return $this->prix_eq;
    }

    public function setPrixEq(int $prix_eq): self
    {
        $this->prix_eq = $prix_eq;

        return $this;
    }

    public function getQuantiteEq(): ?int
    {
        return $this->quantite_eq;
    }

    public function setQuantiteEq(int $quantite_eq): self
    {
        $this->quantite_eq = $quantite_eq;

        return $this;
    }

    public function getImageEq(): ?string
    {
        return $this->image_eq;
    }

    public function setImageEq(string $image_eq): self
    {
        $this->image_eq = $image_eq;

        return $this;
    }

    public function getCategoryId(): ?Categorie
    {
        return $this->category_id;
    }

    public function setCategoryId(?Categorie $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }

  /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setEquipements($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnnonces() === $this) {
                $image->setAnnonces(null);
            }
        }

        return $this;
    }
}
