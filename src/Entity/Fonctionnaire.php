<?php

namespace App\Entity;

use App\Repository\FonctionnaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: FonctionnaireRepository::class)]
class Fonctionnaire implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_FINAL_APPROVER = 'ROLE_FINAL_APPROVER';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $idFonct = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $responsable = false;

    #[ORM\ManyToOne(targetEntity: Organigramme::class, inversedBy: 'fonctionnaires')]
    #[ORM\JoinColumn(name: 'idOrg', referencedColumnName: 'id_org')]
    private ?Organigramme $organigramme = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Clear temporary sensitive data if any
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}