<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PretAvecInteret extends Pret
{
    // Taux annuel, en pourcentage.
    #[ORM\Column]
    private ?float $tauxInteret = null;

    // Avec intérêt : montant + intérêts simples (montant x taux / 100 x durée en mois / 12).
    public function montantTotalARembourser(): float
    {
        $montant = $this->getMontant() ?? 0.0;
        $duree = $this->getDureeEnMois() ?? 0;
        $taux = $this->tauxInteret ?? 0.0;

        return $montant + ($montant * $taux / 100 * $duree / 12);
    }

    public function getTauxInteret(): ?float { return $this->tauxInteret; }
    public function setTauxInteret(float $tauxInteret): static { $this->tauxInteret = $tauxInteret; return $this; }
}