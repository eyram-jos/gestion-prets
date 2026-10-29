<?php

namespace App\DataFixtures;

use App\Entity\Exercice;
use App\Entity\Membre;
use App\Entity\PretAvecInteret;
use App\Entity\PretSansInteret;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PretFixtures extends Fixture implements DependentFixtureInterface
{
    // [membre, exercice, avec ou sans intérêt, montant, durée en mois, date de début, taux annuel (%)]
    public const PRETS = [
        ['membre-0-1', 'exercice-g0-2026', 'sans', 50000.0, 3, '2026-03-10', null],
        ['membre-0-2', 'exercice-g0-2026', 'avec', 100000.0, 6, '2026-04-05', 12.0],
        ['membre-0-3', 'exercice-g0-2026', 'avec', 80000.0, 4, '2026-05-20', 10.0],
        ['membre-1-1', 'exercice-g1-2026', 'avec', 60000.0, 6, '2026-03-01', 12.0],
        ['membre-0-0', 'exercice-g0-2025', 'sans', 30000.0, 2, '2025-03-15', null],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PRETS as [$membre, $exercice, $type, $montant, $duree, $dateDebut, $taux]) {
            $pret = $type === 'avec'
                ? (new PretAvecInteret())->setTauxInteret($taux)
                : new PretSansInteret();

            $pret
                ->setMontant($montant)
                ->setDureeEnMois($duree)
                ->setDateDebut(new \DateTimeImmutable($dateDebut))
                ->setMembre($this->getReference($membre, Membre::class))
                ->setExercice($this->getReference($exercice, Exercice::class));
            $manager->persist($pret);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [MembreFixtures::class, ExerciceFixtures::class];
    }
}