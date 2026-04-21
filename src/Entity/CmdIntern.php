<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CmdInternRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\ApprovalStep;

#[ORM\Entity(repositoryClass: CmdInternRepository::class)]
class CmdIntern
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
private int $idCmdInt ;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateCI = null;

    #[ORM\Column(type: 'string', columnDefinition: "ENUM('Pending','Approved','In Preparation','Ready','Delivered','Rejected')")]
private string $statut = 'Pending';

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idFonct', referencedColumnName: 'id_fonct')]
    private ?Fonctionnaire $fonctionnaire = null;

    #[ORM\OneToMany(mappedBy: 'cmdInt', targetEntity: LigneCmdIntern::class)]
    private Collection $lignesCmdInterns;

    #[ORM\Column(type: 'integer')]
    private int $numero = 0;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $receivedAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'received_by', referencedColumnName: 'id_fonct', nullable: true)]
    private ?Fonctionnaire $receivedBy = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: ApprovalStep::class, cascade: ['persist', 'remove'])]
    private Collection $approvalSteps;

    public function __construct()
    {
        $this->lignesCmdInterns = new ArrayCollection();
        $this->approvalSteps = new ArrayCollection();
    }

    public function getReceivedAt(): ?\DateTimeInterface
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(?\DateTimeInterface $receivedAt): static
    {
        $this->receivedAt = $receivedAt;
        return $this;
    }

    public function getReceivedBy(): ?Fonctionnaire
    {
        return $this->receivedBy;
    }

    public function setReceivedBy(?Fonctionnaire $receivedBy): static
    {
        $this->receivedBy = $receivedBy;
        return $this;
    }

    public function getNumero(): int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;
        return $this;
    }

    public function getApprovalSteps(): Collection
    {
        return $this->approvalSteps;
    }

    public function addApprovalStep(ApprovalStep $step): static
    {
        if (!$this->approvalSteps->contains($step)) {
            $this->approvalSteps->add($step);
            $step->setCommande($this);
        }
        return $this;
    }

    public function removeApprovalStep(ApprovalStep $step): static
    {
        if ($this->approvalSteps->removeElement($step)) {
            if ($step->getCommande() === $this) {
                $step->setCommande(null);
            }
        }
        return $this;
    }

    public function getLignesCmdInterns(): Collection
    {
        return $this->lignesCmdInterns;
    }

    public function getIdCmdInt(): ?int
    {
        return $this->idCmdInt;
    }

    public function getDateCI(): ?\DateTimeInterface
    {
        return $this->dateCI;
    }

    public function setDateCI(\DateTimeInterface $dateCI): static
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