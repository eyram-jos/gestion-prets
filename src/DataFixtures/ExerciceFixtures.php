<?php

namespace App\DataFixtures;

use App\Entity\Exercice;
use App\Entity\Groupement;
use App\Statut\StatutCloture;
use App\Statut\StatutEnCours;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExerciceFixtures extends Fixture implements DependentFixtureInterface
{
    // Clé de référence => [groupement, début, fin, date limite des prêts, rétribution (%), clôturé ?]
    public const EXERCICES = [
        'exercice-g0-2025' => [0, '2025-01-01', '2025-12-31', '2025-09-30', 8.0, true],
        'exercice-g0-2026' => [0, '2026-01-01', '2026-12-31', '2026-09-30', 10.0, false],
        'exercice-g1-2026' => [1, '2026-01-01', '2026-12-31', '2026-09-30', 12.0, false],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::EXERCICES as $cle => [$g, $debut, $fin, $limitePrets, $retribution, $cloture]) {
            $exercice = (new Exercice())
                ->setDateDebut(new \DateTimeImmutable($debut))
                ->setDateFin(new \DateTimeImmutable($fin))
                ->setDateLimitePrets(new \DateTimeImmutable($limitePrets))
                ->setRetribution($retribution)
                ->setStatut($cloture ? new StatutCloture() : new StatutEnCours())
                ->setGroupement($this->getReference('groupement-' . $g, Groupement::class));
            $manager->persist($exercice);
            $this->addReference($cle, $exercice);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [GroupementFixtures::class];
    }
}