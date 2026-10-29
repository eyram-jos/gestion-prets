<?php

namespace App\Statut;

abstract class StatutExercice
{
    // Codes enregistrés en base de données.
    public const EN_COURS = 'en_cours';
    public const CLOTURE = 'cloture';

    abstract public function code(): string;
    abstract public function libelle(): string;
    abstract public function estOuvert(): bool;

    // Fabrique : transforme le code lu en base en objet de la bonne classe.
    public static function depuisCode(string $code): self
    {
        return match ($code) {
            self::EN_COURS => new StatutEnCours(),
            self::CLOTURE => new StatutCloture(),
            default => throw new \InvalidArgumentException('Statut inconnu : ' . $code),
        };
    }
}