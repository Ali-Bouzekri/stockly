<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CmdExternRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: CmdExternRepository::class)]
class CmdExtern
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_cmd_extern', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateCE = null;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('Draft','Ordered','Shipped','Partially Received','Received','Cancelled')")]
        private ?string $statut = 'Draft';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idFournisseur', referencedColumnName: 'id_fournisseur')]
    private ?Fournisseur $fournisseur = null;
#[ORM\Column(type: 'integer')]
private int $numero = 0;

public function getNumero(): int { return $this->numero; }
public function setNumero(int $numero): static { $this->numero = $numero; return $this; }
#[ORM\OneToMany(mappedBy: 'cmdExtern', targetEntity: LigneCmdExtern::class)]
private Collection $lignesCmdExterns;

public function __construct()
{
    $this->lignesCmdExterns = new ArrayCollection();
}

public function getLignesCmdExterns(): Collection
{
    return $this->lignesCmdExterns;
}
    public function getId(): ?int
    {
        return $this->id;
    }

    // kept as alias so templates using idCmdExt still work
    public function getIdCmdExt(): ?int
    {
        return $this->id;
    }

    public function getDateCE(): ?\DateTimeInterface
    {
        return $this->dateCE;
    }

    public function setDateCE(\DateTime $dateCE): static
    {
        $this->dateCE = $dateCE;
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