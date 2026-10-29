<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Seance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numero = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateSeance = null;

    #[ORM\ManyToOne(targetEntity: Campagne::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campagne $campagne = null;

    public function getId(): ?int { return $this->id; }

    public function getNumero(): ?int { return $this->numero; }
    public function setNumero(int $numero): static { $this->numero = $numero; return $this; }

    public function getDateSeance(): ?\DateTimeImmutable { return $this->dateSeance; }
    public function setDateSeance(\DateTimeImmutable $dateSeance): static { $this->dateSeance = $dateSeance; return $this; }

    public function getCampagne(): ?Campagne { return $this->campagne; }
    public function setCampagne(Campagne $campagne): static { $this->campagne = $campagne; return $this; }
}