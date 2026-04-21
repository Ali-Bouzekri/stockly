<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UnitRepository;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idUnite = null;

    #[ORM\Column(length: 255)]
    private ?string $nomUnite = null;

    public function getIdUnite(): ?int
    {
        return $this->idUnite;
    }

    // ✅ alias for Symfony forms
    public function getId(): ?int
    {
        return $this->idUnite;
    }

    public function getNomUnite(): ?string
    {
        return $this->nomUnite;
    }

    public function setNomUnite(string $nomUnite): static
    {
        $this->nomUnite = $nomUnite;

        return $this;
    }
}