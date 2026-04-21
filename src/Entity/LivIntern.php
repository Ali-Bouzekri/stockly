<?php 
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\LivInternRepository;


#[ORM\Entity(repositoryClass: LivInternRepository::class)]
class LivIntern {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column]
    private ?int $idLivInt = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $dateLI = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idCmdIntern', referencedColumnName: 'id_cmd_int')]
    private ?CmdIntern $cmdInt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'idFonctionnaire', referencedColumnName: 'id_fonct')]
    private ?Fonctionnaire $receveur = null;

    public function getIdLivInt(): ?int
    {
        return $this->idLivInt;
    }

    public function getDateLI(): ?\DateTime
    {
        return $this->dateLI;
    }

    public function setDateLI(\DateTime $dateLI): static
    {
        $this->dateLI = $dateLI;

        return $this;
    }

    public function getCmdInt(): ?CmdIntern
    {
        return $this->cmdInt;
    }

    public function setCmdInt(?CmdIntern $cmdInt): static
    {
        $this->cmdInt = $cmdInt;

        return $this;
    }

    public function getReceveur(): ?Fonctionnaire
    {
        return $this->receveur;
    }

    public function setReceveur(?Fonctionnaire $receveur): static
    {
        $this->receveur = $receveur;

        return $this;
    }
}