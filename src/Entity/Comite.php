<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ComiteRepository;
use App\Entity\Fournisseur;

#[ORM\Entity(repositoryClass: ComiteRepository::class)]
class Comite {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $idComit = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idFor', referencedColumnName: 'id_fournisseur')]
    private ?Fournisseur $fournisseur = null;

    public function getIdComit(): ?int
    {
        return $this->idComit;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): static
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }
}