<?php

namespace App\Repository;

use App\Entity\Groupement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Groupement>
 */
class GroupementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupement::class);
    }

    // Requête personnalisée : filtre par téléphone et par ville (cherchée dans l'adresse),
    // trie par nom et ne renvoie que les groupements de la page demandée.
    public function rechercher(?string $tel, ?string $ville, int $page, int $parPage): Paginator
    {
        $qb = $this->createQueryBuilder('g')->orderBy('g.nom', 'ASC');

        if ($tel !== null && $tel !== '') {
            $qb->andWhere('g.tel LIKE :tel')->setParameter('tel', '%' . $tel . '%');
        }

        if ($ville !== null && $ville !== '') {
            $qb->andWhere('LOWER(g.adresse) LIKE :ville')->setParameter('ville', '%' . mb_strtolower($ville) . '%');
        }

        $qb->setFirstResult(($page - 1) * $parPage)->setMaxResults($parPage);

        // Le Paginator compte aussi le total des résultats sans tenir compte de la page.
        return new Paginator($qb, false);
    }
}