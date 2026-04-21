<?php 
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CmdExternRepository;
use App\Entity\Fournisseur;


#[ORM\Entity(repositoryClass: CmdExternRepository::class)]
class CmdExtern {
    #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(name: "id_cmd_extern", type: "integer")]
private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateCE = null;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('Brouillon', 'Commandée', 'Expédiée', 'Réceptionnée Partiellement', 'Réceptionnée', 'Annulée')")]
    private ?string $statut = 'Brouillon';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idFournisseur', referencedColumnName: 'id_fournisseur')]
    private ?Fournisseur $fournisseur = null;

    public function getId(): ?int
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
