<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomRole = null;

    public function getId(): ?int { return $this->id; }

    public function getNomRole(): ?string { return $this->nomRole; }
    public function setNomRole(string $nomRole): static { $this->nomRole = $nomRole; return $this; }
}