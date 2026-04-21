<?php 
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FournisseurRepository;

#[ORM\Entity(repositoryClass: FournisseurRepository::class)]
class Fournisseur {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $idFournisseur = null;

    #[ORM\Column(length: 255)]
    private ?string $denominateur = null;

    #[ORM\Column(length: 255)]
    private ?string $contact = null;

    #[ORM\Column(type: 'text')]
    private ?string $adresse = null;

    public function getIdFournisseur(): ?int
    {
        return $this->idFournisseur;
    }

    public function getDenominateur(): ?string
    {
        return $this->denominateur;
    }

    public function setDenominateur(string $denominateur): static
    {
        $this->denominateur = $denominateur;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }
}