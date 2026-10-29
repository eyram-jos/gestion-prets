<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PretSansInteret extends Pret
{
    public function libelleType(): string
    {
        return 'Sans intérêt';
    }

    // Sans intérêt : on rembourse exactement ce qui a été emprunté.
    public function montantTotalARembourser(): float
    {
        return $this->getMontant() ?? 0.0;
    }
}