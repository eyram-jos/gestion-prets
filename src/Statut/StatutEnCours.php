<?php

namespace App\Statut;

class StatutEnCours extends StatutExercice
{
    public function code(): string { return self::EN_COURS; }
    public function libelle(): string { return 'En cours'; }
    public function estOuvert(): bool { return true; }
}