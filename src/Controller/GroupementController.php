<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Entity\Groupement;
use App\Entity\Membre;
use App\Statut\StatutExercice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GroupementController extends AbstractController
{
    // Symfony nous « donne » le gestionnaire d'entités (injection de dépendances).
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/', name: 'accueil')]
    public function accueil(): Response
    {
        return $this->redirectToRoute('groupement_index');
    }

    // Liste des groupements, avec le nombre de membres et d'exercices en cours.
    #[Route('/groupements', name: 'groupement_index')]
    public function index(): Response
    {
        $lignes = [];
        foreach ($this->em->getRepository(Groupement::class)->findBy([], ['nom' => 'ASC']) as $groupement) {
            $lignes[] = [
                'groupement' => $groupement,
                'nbMembres' => $this->em->getRepository(Membre::class)->count(['groupement' => $groupement]),
                'nbExercicesEnCours' => $this->em->getRepository(Exercice::class)->count([
                    'groupement' => $groupement,
                    'statut' => StatutExercice::EN_COURS,
                ]),
            ];
        }

        return $this->render('groupement/index.html.twig', ['lignes' => $lignes]);
    }

    // Fiche d'un groupement : ses exercices en cours, puis ses exercices clôturés.
    #[Route('/groupements/{id}', name: 'groupement_show', requirements: ['id' => '\d+'])]
    public function show(Groupement $groupement): Response
    {
        $exercices = $this->em->getRepository(Exercice::class)
            ->findBy(['groupement' => $groupement], ['dateDebut' => 'DESC']);

        return $this->render('groupement/show.html.twig', [
            'groupement' => $groupement,
            'enCours' => array_filter($exercices, fn (Exercice $e) => $e->getStatut()->estOuvert()),
            'clotures' => array_filter($exercices, fn (Exercice $e) => !$e->getStatut()->estOuvert()),
        ]);
    }

    // Liste des membres d'un groupement.
    #[Route('/groupements/{id}/membres', name: 'groupement_membres', requirements: ['id' => '\d+'])]
    public function membres(Groupement $groupement): Response
    {
        $membres = $this->em->getRepository(Membre::class)
            ->findBy(['groupement' => $groupement], ['nom' => 'ASC']);

        return $this->render('groupement/membres.html.twig', [
            'groupement' => $groupement,
            'membres' => $membres,
        ]);
    }
}
