<?php

namespace App\DataFixtures;

use App\Entity\Membre;
use App\Entity\ModeCotisation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ModeCotisationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Tous les membres d'un groupement participent aux campagnes de ce groupement.
        foreach (CampagneFixtures::CAMPAGNES as $cle => $infos) {
            // La reference est rangee sous la classe exacte (Tontine ou Epargne), pas sous Campagne.
            $campagne = $this->getReference($cle, $infos['classe']);
            $g = $infos['groupement'];

            foreach (array_keys(MembreFixtures::MEMBRES[$g]) as $m) {
                $mode = (new ModeCotisation())
                    ->setIsLibre($infos['libre'])
                    ->setMembre($this->getReference('membre-' . $g . '-' . $m, Membre::class))
                    ->setCampagne($campagne);
                $manager->persist($mode);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [MembreFixtures::class, CampagneFixtures::class];
    }
}