<?php 
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\LigneCmdExternRepository;
use App\Entity\CmdExtern;
use App\Entity\Produit;



#[ORM\Entity(repositoryClass: LigneCmdExternRepository::class)]
class LigneCmdExtern {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $numL_CE = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(targetEntity: CmdExtern::class)]
#[ORM\JoinColumn(name: "id_cmd_extern", referencedColumnName: "id_cmd_extern", nullable: false)]
private ?CmdExtern $cmdExtern = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idProduit', referencedColumnName: 'id_produit')]
    private ?Produit $produit = null;

    public function getNumLCE(): ?int
    {
        return $this->numL_CE;
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

    public function getCmdExtern(): ?CmdExtern
    {
        return $this->cmdExtern;
    }

    public function setCmdExtern(?CmdExtern $cmdExtern): static
    {
        $this->cmdExtern = $cmdExtern;

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
