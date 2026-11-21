<?php

namespace App\Dto;

// DTO de sortie : une ligne de la liste des groupements.
// Les propriétés sont en lecture seule : une fois créée, la ligne ne change plus.
class GroupementLigneDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $nom,
        public readonly string $tel,
        public readonly string $adresse,
        public readonly int $nbMembres,
        public readonly int $nbExercicesEnCours,
    ) {
    }
}