<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Tontine extends Campagne
{
    public function libelleType(): string
    {
        return 'Tontine';
    }
}