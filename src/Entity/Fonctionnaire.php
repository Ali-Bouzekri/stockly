<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FonctionnaireRepository;
use App\Entity\Organigramme;

#[ORM\Entity(repositoryClass: FonctionnaireRepository::class)]
class Fonctionnaire {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $idFonct = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $responsable = false;

    #[ORM\ManyToOne(targetEntity: Organigramme::class, inversedBy: 'fonctionnaires')]
    #[ORM\JoinColumn(name: 'idOrg', referencedColumnName: 'id_org')]
    private ?Organigramme $organigramme = null;

    public function getIdFonct(): ?int
    {
        return $this->idFonct;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isResponsable(): ?bool
    {
        return $this->responsable;
    }

    public function setResponsable(bool $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getOrganigramme(): ?Organigramme
    {
        return $this->organigramme;
    }

    public function setOrganigramme(?Organigramme $organigramme): static
    {
        $this->organigramme = $organigramme;

        return $this;
    }
}