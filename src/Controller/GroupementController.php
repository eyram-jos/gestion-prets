<?php

namespace App\Controller;

use App\Dto\GroupementFiltreDto;
use App\Entity\Exercice;
use App\Entity\Groupement;
use App\Entity\Membre;
use App\Form\GroupementFiltreType;
use App\Service\GroupementService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    // Liste des groupements : cette fonction récupère le DTO, le confie au service qui filtre
    // et pagine, puis affiche le résultat. Elle ne fait aucun calcul elle-même.
    #[Route('/groupements', name: 'groupement_index')]
    public function index(Request $request, GroupementService $service): Response
    {
        // Le DTO d'entrée est rempli par le formulaire de filtre (téléphone et ville)...
        $filtre = new GroupementFiltreDto();
        $form = $this->createForm(GroupementFiltreType::class, $filtre);
        $form->handleRequest($request);

        // ... et par le numéro de page, lu dans l'adresse (une valeur invalide donne la page 1).
        $filtre->setPage((int) $request->query->get('page', 1));

        // Le service filtre, pagine et renvoie le DTO de sortie.
        $liste = $service->liste($filtre);

        return $this->render('groupement/index.html.twig', [
            'form' => $form->createView(),
            'liste' => $liste,
            'filtre' => $filtre,
        ]);
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