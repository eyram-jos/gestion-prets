<?php

namespace App\DataFixtures;

use App\Entity\Groupement;
use App\Entity\Membre;
use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MembreFixtures extends Fixture implements DependentFixtureInterface
{
    // Pour chaque groupement : prénom, nom, fonction, index du rôle.
    public const MEMBRES = [
        0 => [
            ['Awa', 'Diop', 'Commerçante', 0],
            ['Fatou', 'Ndiaye', 'Couturière', 1],
            ['Aminata', 'Sow', 'Enseignante', 2],
            ['Khady', 'Fall', 'Restauratrice', 3],
            ['Mariama', 'Ba', 'Coiffeuse', 3],
        ],
        1 => [
            ['Ndeye', 'Sarr', 'Teinturière', 0],
            ['Aissatou', 'Diallo', 'Commerçante', 1],
            ['Coumba', 'Gueye', 'Vendeuse', 3],
        ],
        2 => [
            ['Rokhaya', 'Thiam', 'Maraîchère', 0],
            ['Sokhna', 'Mbaye', 'Couturière', 3],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::MEMBRES as $g => $membres) {
            foreach ($membres as $m => [$prenom, $nom, $fonction, $indexRole]) {
                $membre = (new Membre())
                    ->setNom($nom)
                    ->setPrenom($prenom)
                    ->setTel(sprintf('+221 77 %03d %02d %02d', 400 + $g * 10 + $m, $g, $m))
                    ->setAdresse('Quartier ' . ($m + 1))
                    ->setLogin(strtolower($prenom . '.' . $nom))
                    ->setFonction($fonction)
                    ->setRole($this->getReference('role-' . $indexRole, Role::class))
                    ->setGroupement($this->getReference('groupement-' . $g, Groupement::class));
                $manager->persist($membre);
                $this->addReference('membre-' . $g . '-' . $m, $membre);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [RoleFixtures::class, GroupementFixtures::class];
    }
}