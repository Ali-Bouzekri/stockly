<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CmdInternRepository;

#[ORM\Entity(repositoryClass: CmdInternRepository::class)]
class CmdIntern {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $idCmdInt = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateCI = null;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('Prête', 'En cours de préparation', 'Rejetée', 'Approuvée', 'En attente', 'Livrée')")]
    private ?string $statut = 'En attente';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idFonct', referencedColumnName: 'id_fonct')]
    private ?Fonctionnaire $fonctionnaire = null;

    public function getIdCmdInt(): ?int
    {
        return $this->idCmdInt;
    }

    public function getDateCI(): ?\DateTime
    {
        return $this->dateCI;
    }

    public function setDateCI(\DateTime $dateCI): static
    {
        $this->dateCI = $dateCI;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getFonctionnaire(): ?Fonctionnaire
    {
        return $this->fonctionnaire;
    }

    public function setFonctionnaire(?Fonctionnaire $fonctionnaire): static
    {
        $this->fonctionnaire = $fonctionnaire;

        return $this;
    }
}