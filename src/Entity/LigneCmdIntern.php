<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LigneCmdInternRepository;
use App\Entity\CmdIntern;


#[ORM\Entity(repositoryClass: LigneCmdInternRepository::class)]
class LigneCmdIntern {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $numL_CI = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idCi', referencedColumnName: 'id_cmd_int')]
    private ?CmdIntern $cmdInt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idProduit', referencedColumnName: 'id_produit')]
    private ?Produit $produit = null;

    public function getNumLCI(): ?int
    {
        return $this->numL_CI;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCmdInt(): ?CmdIntern
    {
        return $this->cmdInt;
    }

    public function setCmdInt(?CmdIntern $cmdInt): static
    {
        $this->cmdInt = $cmdInt;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}