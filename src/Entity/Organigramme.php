<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\OrganigrammeRepository;

#[ORM\Entity(repositoryClass: OrganigrammeRepository::class)]
class Organigramme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_org', type: 'integer')]
    private ?int $idOrg = null;

    #[ORM\Column(length: 255)]
    private ?string $departement = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'id_parent', referencedColumnName: 'id_org', nullable: true)]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $children;

    #[ORM\OneToMany(mappedBy: 'organigramme', targetEntity: Fonctionnaire::class)]
    private Collection $fonctionnaires;

    public function __construct()
    {
        $this->fonctionnaires = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getIdOrg(): ?int { return $this->idOrg; }

    public function getDepartement(): ?string { return $this->departement; }
    public function setDepartement(string $departement): static { $this->departement = $departement; return $this; }

    public function getParent(): ?self { return $this->parent; }
    public function setParent(?self $parent): static { $this->parent = $parent; return $this; }

    public function getChildren(): Collection { return $this->children; }
    public function addChild(self $child): static { if (!$this->children->contains($child)) { $this->children->add($child); $child->setParent($this); } return $this; }
    public function removeChild(self $child): static { if ($this->children->removeElement($child) && $child->getParent() === $this) { $child->setParent(null); } return $this; }

    public function getFonctionnaires(): Collection { return $this->fonctionnaires; }
    public function addFonctionnaire(Fonctionnaire $fonctionnaire): static { if (!$this->fonctionnaires->contains($fonctionnaire)) { $this->fonctionnaires->add($fonctionnaire); $fonctionnaire->setOrganigramme($this); } return $this; }
    public function removeFonctionnaire(Fonctionnaire $fonctionnaire): static { if ($this->fonctionnaires->removeElement($fonctionnaire) && $fonctionnaire->getOrganigramme() === $this) { $fonctionnaire->setOrganigramme(null); } return $this; }
}