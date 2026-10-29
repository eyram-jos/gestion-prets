<?php

namespace App\Statut;

class StatutCloture extends StatutExercice
{
    public function code(): string { return self::CLOTURE; }
    public function libelle(): string { return 'Clôturé'; }
    public function estOuvert(): bool { return false; }
}