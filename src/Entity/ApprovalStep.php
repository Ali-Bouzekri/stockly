<?php

namespace App\Entity;

use App\Repository\ApprovalStepRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApprovalStepRepository::class)]
class ApprovalStep
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'approvalSteps')]
    #[ORM\JoinColumn(name: 'commande_id', referencedColumnName: 'id_cmd_int', nullable: false)]
    private ?CmdIntern $commande = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'approver_id', referencedColumnName: 'id_fonct', nullable: false)]
    private ?Fonctionnaire $approver = null;

    #[ORM\Column]
    private int $stepOrder = 0;

    #[ORM\Column(length: 20)]
    private string $status = 'pending';

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $approvedAt = null;

    #[ORM\Column(type: 'text', nullable: true)]
private ?string $comments = null;
    // Getters and setters for all properties...
    public function getId(): ?int { return $this->id; }
    public function getCommande(): ?CmdIntern { return $this->commande; }
    public function setCommande(?CmdIntern $commande): static { $this->commande = $commande; return $this; }
    public function getApprover(): ?Fonctionnaire { return $this->approver; }
    public function setApprover(?Fonctionnaire $approver): static { $this->approver = $approver; return $this; }
    public function getStepOrder(): int { return $this->stepOrder; }
    public function setStepOrder(int $stepOrder): static { $this->stepOrder = $stepOrder; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }
    public function getApprovedAt(): ?\DateTimeInterface { return $this->approvedAt; }
    public function setApprovedAt(?\DateTimeInterface $approvedAt): static { $this->approvedAt = $approvedAt; return $this; }
    public function getComments(): ?string { return $this->comments; }
    public function setComments(?string $comments): static { $this->comments = $comments; return $this; }
}