<?php

namespace App\Service;

use App\Dto\GroupementFiltreDto;
use App\Dto\GroupementLigneDto;
use App\Dto\GroupementPageDto;
use App\Entity\Groupement;
use App\Repository\ExerciceRepository;
use App\Repository\GroupementRepository;
use App\Repository\MembreRepository;

class GroupementService
{
    // Symfony nous « donne » les trois repositories (injection de dépendances).
    public function __construct(
        private GroupementRepository $groupementRepository,
        private MembreRepository $membreRepository,
        private ExerciceRepository $exerciceRepository,
    ) {
    }

    // La fonction qui affiche la liste est aussi celle qui filtre et qui pagine :
    // elle reçoit le DTO d'entrée (critères et page) et renvoie le DTO de sortie (une page).
    public function liste(GroupementFiltreDto $filtre): GroupementPageDto
    {
        $parPage = GroupementFiltreDto::PAR_PAGE;
        $page = $filtre->getPage();

        // Filtre et pagination : une seule requête personnalisée.
        $resultat = $this->groupementRepository->rechercher($filtre->getTel(), $filtre->getVille(), $page, $parPage);
        $total = count($resultat);

        // Si la page demandée dépasse la dernière page, on affiche la dernière page.
        $nbPages = max(1, (int) ceil($total / $parPage));
        if ($page > $nbPages) {
            $page = $nbPages;
            $resultat = $this->groupementRepository->rechercher($filtre->getTel(), $filtre->getVille(), $page, $parPage);
        }

        $groupements = iterator_to_array($resultat);
        $ids = array_map(fn (Groupement $groupement) => $groupement->getId(), $groupements);

        // Les nombres de membres et d'exercices en cours des groupements de la page, en deux requêtes.
        $nbMembres = $this->membreRepository->compterParGroupements($ids);
        $nbExercicesEnCours = $this->exerciceRepository->compterEnCoursParGroupements($ids);

        // On transforme chaque entité en ligne de DTO : le gabarit n'a plus besoin des entités.
        $lignes = array_map(
            fn (Groupement $groupement) => new GroupementLigneDto(
                $groupement->getId(),
                $groupement->getNom(),
                $groupement->getTel(),
                $groupement->getAdresse(),
                $nbMembres[$groupement->getId()] ?? 0,
                $nbExercicesEnCours[$groupement->getId()] ?? 0,
            ),
            array_values($groupements),
        );

        return new GroupementPageDto($lignes, $page, $parPage, $total);
    }
}