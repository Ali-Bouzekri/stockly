<?php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $idProduit = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $qteStock = 0;

    #[ORM\Column]
    private ?int $seuilAlert = 0;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idUnite', referencedColumnName: 'id_unite')]
    private ?Unit $unite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idSousCat', referencedColumnName: 'id_sous_cat')]
    private ?SousCategorie $sousCategorie = null;

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQteStock(): ?int
    {
        return $this->qteStock;
    }

    public function setQteStock(int $qteStock): static
    {
        $this->qteStock = $qteStock;

        return $this;
    }

    public function getSeuilAlert(): ?int
    {
        return $this->seuilAlert;
    }

    public function setSeuilAlert(int $seuilAlert): static
    {
        $this->seuilAlert = $seuilAlert;

        return $this;
    }

    public function getUnite(): ?Unit
    {
        return $this->unite;
    }

    public function setUnite(?Unit $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getSousCategorie(): ?SousCategorie
    {
        return $this->sousCategorie;
    }

    public function setSousCategorie(?SousCategorie $sousCategorie): static
    {
        $this->sousCategorie = $sousCategorie;

        return $this;
    }
}