<?php

namespace App\Repository;

use App\Entity\Membre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Membre>
 */
class MembreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Membre::class);
    }

    /**
     * Nombre de membres de chaque groupement, en une seule requête.
     *
     * @param int[] $groupementIds
     * @return array<int, int> nombre de membres, avec l'identifiant du groupement pour clé
     */
    public function compterParGroupements(array $groupementIds): array
    {
        if ($groupementIds === []) {
            return [];
        }

        $lignes = $this->createQueryBuilder('m')
            ->select('IDENTITY(m.groupement) AS groupementId', 'COUNT(m.id) AS nb')
            ->where('m.groupement IN (:ids)')
            ->setParameter('ids', $groupementIds)
            ->groupBy('m.groupement')
            ->getQuery()
            ->getArrayResult();

        $nombres = [];
        foreach ($lignes as $ligne) {
            $nombres[(int) $ligne['groupementId']] = (int) $ligne['nb'];
        }

        return $nombres;
    }
}