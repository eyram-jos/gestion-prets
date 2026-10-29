<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Cotisation extends Operation
{
    // Une cotisation compte entièrement comme cotisation, et pas comme amende.
    public function montantCotise(): float
    {
        return $this->getMontant() ?? 0.0;
    }

    public function montantAmende(): float
    {
        return 0.0;
    }
}