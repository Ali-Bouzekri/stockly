<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SousCategorieRepository;


#[ORM\Entity(repositoryClass: SousCategorieRepository::class)]
class SousCategorie {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $idSousCat = null;

    #[ORM\Column(length: 255)]
    private ?string $nomSousCat = null;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'sousCategories')]
    #[ORM\JoinColumn(name: 'idCategory', referencedColumnName: 'id_category')]
    private ?Categorie $categorie = null;

    public function getIdSousCat(): ?int
    {
        return $this->idSousCat;
    }

    public function getNomSousCat(): ?string
    {
        return $this->nomSousCat;
    }
    public function getId(): ?int
    {
        return $this->idSousCat;
    }
    public function setNomSousCat(string $nomSousCat): static
    {
        $this->nomSousCat = $nomSousCat;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }
}