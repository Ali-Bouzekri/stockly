<?php 
namespace App\Entity;

use App\Repository\LigneLivInternRepository;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: LigneLivInternRepository::class)]
class LigneLivIntern {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $numL_LI = null;

    #[ORM\Column]
    private ?int $qteLivree = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idLivInt', referencedColumnName: 'id_liv_int')]
    private ?LivIntern $livInt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idProduit', referencedColumnName: 'id_produit')]
    private ?Produit $produit = null;

    public function getNumLLI(): ?int
    {
        return $this->numL_LI;
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

    public function getLivInt(): ?LivIntern
    {
        return $this->livInt;
    }

    public function setLivInt(?LivIntern $livInt): static
    {
        $this->livInt = $livInt;

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