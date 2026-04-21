<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\OrganigrammeRepository;


#[ORM\Entity(repositoryClass: OrganigrammeRepository::class)]
class Organigramme {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $idOrg = null;

    #[ORM\Column(length: 255)]
    private ?string $departement = null;

    #[ORM\OneToMany(mappedBy: 'organigramme', targetEntity: Fonctionnaire::class)]
    private Collection $fonctionnaires;

    public function __construct() { $this->fonctionnaires = new ArrayCollection(); }

    public function getIdOrg(): ?int
    {
        return $this->idOrg;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection<int, Fonctionnaire>
     */
    public function getFonctionnaires(): Collection
    {
        return $this->fonctionnaires;
    }

    public function addFonctionnaire(Fonctionnaire $fonctionnaire): static
    {
        if (!$this->fonctionnaires->contains($fonctionnaire)) {
            $this->fonctionnaires->add($fonctionnaire);
            $fonctionnaire->setOrganigramme($this);
        }

        return $this;
    }

    public function removeFonctionnaire(Fonctionnaire $fonctionnaire): static
    {
        if ($this->fonctionnaires->removeElement($fonctionnaire)) {
            // set the owning side to null (unless already changed)
            if ($fonctionnaire->getOrganigramme() === $this) {
                $fonctionnaire->setOrganigramme(null);
            }
        }

        return $this;
    }
}