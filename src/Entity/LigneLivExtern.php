<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LigneLivExternRepository;
use App\Entity\LivExtern;

#[ORM\Entity(repositoryClass: LigneLivExternRepository::class)]
class LigneLivExtern {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $numL_LE = null;

    #[ORM\Column]
    private ?int $qteLivree = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idLivExt', referencedColumnName: 'id_liv_ext')]
    private ?LivExtern $livExt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idProduit', referencedColumnName: 'id_produit')]
    private ?Produit $produit = null;

    public function getNumLLE(): ?int
    {
        return $this->numL_LE;
    }

    public function getQteLivree(): ?int
    {
        return $this->qteLivree;
    }

    public function setQteLivree(int $qteLivree): static
    {
        $this->qteLivree = $qteLivree;

        return $this;
    }

    public function getLivExt(): ?LivExtern
    {
        return $this->livExt;
    }

    public function setLivExt(?LivExtern $livExt): static
    {
        $this->livExt = $livExt;

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