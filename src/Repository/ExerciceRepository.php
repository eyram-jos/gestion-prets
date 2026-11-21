<?php

namespace App\Repository;

use App\Entity\Exercice;
use App\Statut\StatutExercice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exercice>
 */
class ExerciceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercice::class);
    }

    /**
     * Nombre d'exercices en cours de chaque groupement, en une seule requête.
     *
     * @param int[] $groupementIds
     * @return array<int, int> nombre d'exercices en cours, avec l'identifiant du groupement pour clé
     */
    public function compterEnCoursParGroupements(array $groupementIds): array
    {
        if ($groupementIds === []) {
            return [];
        }

        $lignes = $this->createQueryBuilder('e')
            ->select('IDENTITY(e.groupement) AS groupementId', 'COUNT(e.id) AS nb')
            ->where('e.groupement IN (:ids)')
            ->andWhere('e.statut = :statut')
            ->setParameter('ids', $groupementIds)
            ->setParameter('statut', StatutExercice::EN_COURS)
            ->groupBy('e.groupement')
            ->getQuery()
            ->getArrayResult();

        $nombres = [];
        foreach ($lignes as $ligne) {
            $nombres[(int) $ligne['groupementId']] = (int) $ligne['nb'];
        }

        return $nombres;
    }
}