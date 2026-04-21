<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\CategorieRepository;
use App\Entity\SousCategorie;


#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $idCategory = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCategory = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: SousCategorie::class)]
    private Collection $sousCategories;

    public function __construct() { $this->sousCategories = new ArrayCollection(); }

    public function getIdCategory(): ?int
    {
        return $this->idCategory;
    }

    public function getNomCategory(): ?string
    {
        return $this->nomCategory;
    }

    public function setNomCategory(string $nomCategory): static
    {
        $this->nomCategory = $nomCategory;

        return $this;
    }

    /**
     * @return Collection<int, SousCategorie>
     */
    public function getSousCategories(): Collection
    {
        return $this->sousCategories;
    }

    public function addSousCategory(SousCategorie $sousCategory): static
    {
        if (!$this->sousCategories->contains($sousCategory)) {
            $this->sousCategories->add($sousCategory);
            $sousCategory->setCategorie($this);
        }

        return $this;
    }

    public function removeSousCategory(SousCategorie $sousCategory): static
    {
        if ($this->sousCategories->removeElement($sousCategory)) {
            // set the owning side to null (unless already changed)
            if ($sousCategory->getCategorie() === $this) {
                $sousCategory->setCategorie(null);
            }
        }

        return $this;
    }
}