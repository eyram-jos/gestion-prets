<?php

namespace App\DataFixtures;

use App\Entity\Groupement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupementFixtures extends Fixture
{
    // Le troisième groupement n'a volontairement aucun exercice (cas de la liste vide).
    public const GROUPEMENTS = [
        ['Avec les Femmes de Pikine', '+221 77 100 20 30', 'Pikine, Dakar'],
        ['Jappo Liggeey de Dakar', '+221 77 200 30 40', 'Médina, Dakar'],
        ['Teranga Solidarité de Thiès', '+221 77 300 40 50', 'Thiès'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::GROUPEMENTS as $i => [$nom, $tel, $adresse]) {
            $groupement = (new Groupement())
                ->setNom($nom)
                ->setTel($tel)
                ->setAdresse($adresse);
            $manager->persist($groupement);
            $this->addReference('groupement-' . $i, $groupement);
        }

        $manager->flush();
    }
}