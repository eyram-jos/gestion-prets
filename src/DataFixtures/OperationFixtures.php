<?php

namespace App\DataFixtures;

use App\Entity\Amende;
use App\Entity\Cotisation;
use App\Entity\Membre;
use App\Entity\Seance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OperationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach (CampagneFixtures::CAMPAGNES as $cle => $infos) {
            $g = $infos['groupement'];
            $nombreMembres = count(MembreFixtures::MEMBRES[$g]);

            for ($n = 1; $n <= SeanceFixtures::NB_SEANCES; $n++) {
                $seance = $this->getReference('seance-' . $cle . '-' . $n, Seance::class);

                for ($m = 0; $m < $nombreMembres; $m++) {
                    $membre = $this->getReference('membre-' . $g . '-' . $m, Membre::class);

                    // Cotisation fixe, ou variable quand la cotisation est libre.
                    $montant = $infos['libre']
                        ? $infos['montant'] * (1 + ($m + $n) % 3)
                        : $infos['montant'];
                    $manager->persist(
                        (new Cotisation())->setMontant($montant)->setMembre($membre)->setSeance($seance)
                    );

                    // Quelques amendes, toujours aux mêmes endroits pour des données reproductibles.
                    if ($n === 1 && $m === 2) {
                        $manager->persist(
                            (new Amende())->setMontant(500.0)->setMotif('Retard à la séance')
                                ->setMembre($membre)->setSeance($seance)
                        );
                    }
                    if ($n === 2 && $m === 1) {
                        $manager->persist(
                            (new Amende())->setMontant(1000.0)->setMotif('Absence non justifiée')
                                ->setMembre($membre)->setSeance($seance)
                        );
                    }
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [SeanceFixtures::class, MembreFixtures::class];
    }
}