<?php

namespace App\DataFixtures;

use App\Entity\Epargne;
use App\Entity\Exercice;
use App\Entity\Tontine;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CampagneFixtures extends Fixture implements DependentFixtureInterface
{
    // Clé de référence => description de la campagne.
    // 'libre' : la cotisation est-elle libre (ModeCotisation) ? 'montant' : montant par séance.
    public const CAMPAGNES = [
        'campagne-g0-2025-epargne' => [
            'classe' => Epargne::class, 'nom' => 'Épargne Rentrée 2025', 'groupement' => 0,
            'exercice' => 'exercice-g0-2025', 'debut' => '2025-02-01', 'fin' => '2025-07-31',
            'montant' => 5000.0, 'libre' => true,
        ],
        'campagne-g0-2026-tontine' => [
            'classe' => Tontine::class, 'nom' => 'Tontine Tabaski 2026', 'groupement' => 0,
            'exercice' => 'exercice-g0-2026', 'debut' => '2026-02-01', 'fin' => '2026-07-31',
            'montant' => 10000.0, 'libre' => false,
        ],
        'campagne-g0-2026-epargne' => [
            'classe' => Epargne::class, 'nom' => 'Épargne Scolaire 2026', 'groupement' => 0,
            'exercice' => 'exercice-g0-2026', 'debut' => '2026-03-01', 'fin' => '2026-10-31',
            'montant' => 5000.0, 'libre' => true,
        ],
        'campagne-g1-2026-tontine' => [
            'classe' => Tontine::class, 'nom' => 'Tontine Mensuelle 2026', 'groupement' => 1,
            'exercice' => 'exercice-g1-2026', 'debut' => '2026-01-15', 'fin' => '2026-12-15',
            'montant' => 15000.0, 'libre' => false,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CAMPAGNES as $cle => $infos) {
            $classe = $infos['classe'];
            $campagne = new $classe();
            $campagne
                ->setNom($infos['nom'])
                ->setDateDebut(new \DateTimeImmutable($infos['debut']))
                ->setDateFin(new \DateTimeImmutable($infos['fin']))
                ->setMontant($infos['montant'])
                ->setExercice($this->getReference($infos['exercice'], Exercice::class));
            $manager->persist($campagne);
            $this->addReference($cle, $campagne);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [ExerciceFixtures::class];
    }
}