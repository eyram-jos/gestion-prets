<?php

namespace App\DataFixtures;

use App\Entity\Campagne;
use App\Entity\Seance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeanceFixtures extends Fixture implements DependentFixtureInterface
{
    public const NB_SEANCES = 3;

    public function load(ObjectManager $manager): void
    {
        // Une séance par mois, à partir de la date de début de la campagne.
        foreach (CampagneFixtures::CAMPAGNES as $cle => $infos) {
            $campagne = $this->getReference($cle, Campagne::class);
            $debut = new \DateTimeImmutable($infos['debut']);

            for ($n = 1; $n <= self::NB_SEANCES; $n++) {
                $seance = (new Seance())
                    ->setNumero($n)
                    ->setDateSeance($debut->modify('+' . ($n - 1) . ' month'))
                    ->setCampagne($campagne);
                $manager->persist($seance);
                $this->addReference('seance-' . $cle . '-' . $n, $seance);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CampagneFixtures::class];
    }
}