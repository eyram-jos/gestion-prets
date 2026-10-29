<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public const ROLES = ['Présidente', 'Trésorière', 'Secrétaire', 'Membre'];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROLES as $i => $nom) {
            $role = (new Role())->setNomRole($nom);
            $manager->persist($role);
            $this->addReference('role-' . $i, $role);
        }

        $manager->flush();
    }
}