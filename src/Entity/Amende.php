<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Amende extends Operation
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif = null;

    public function getMotif(): ?string { return $this->motif; }
    public function setMotif(?string $motif): static { $this->motif = $motif; return $this; }
}