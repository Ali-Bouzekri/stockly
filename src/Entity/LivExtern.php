<?php 
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivExternRepository;


#[ORM\Entity(repositoryClass: LivExternRepository::class)]
class LivExtern
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idLivExt = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateLE = null;

    #[ORM\ManyToOne(targetEntity: CmdExtern::class)]
    #[ORM\JoinColumn(name: "id_cmd_extern", referencedColumnName: "id_cmd_extern", nullable: false)]
    private ?CmdExtern $cmdExtern = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idLext', referencedColumnName: 'id_comit')]
    private ?Comite $comite = null;

    public function getIdLivExt(): ?int
    {
        return $this->idLivExt;
    }

    public function getDateLE(): ?\DateTimeInterface
    {
        return $this->dateLE;
    }

    public function setDateLE(\DateTimeInterface $dateLE): static
    {
        $this->dateLE = $dateLE;
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

    public function getComite(): ?Comite
    {
        return $this->comite;
    }

    public function setComite(?Comite $comite): static
    {
        $this->comite = $comite;
        return $this;
    }
}