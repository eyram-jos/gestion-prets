<?php

namespace App\Controller;

use App\Entity\Campagne;
use App\Entity\ModeCotisation;
use App\Entity\Operation;
use App\Entity\Seance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CampagneController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    // Une campagne : ses membres, puis séance par séance les cotisations, les amendes et les cumuls.
    #[Route('/campagnes/{id}', name: 'campagne_show', requirements: ['id' => '\d+'])]
    public function show(Campagne $campagne): Response
    {
        $modes = $this->em->getRepository(ModeCotisation::class)->findBy(['campagne' => $campagne]);
        $seances = $this->em->getRepository(Seance::class)->findBy(['campagne' => $campagne], ['numero' => 'ASC']);

        // Les membres de la campagne, triés par nom (la clé est l'identifiant du membre).
        $membres = [];
        foreach ($modes as $mode) {
            $membres[$mode->getMembre()->getId()] = $mode;
        }
        uasort($membres, fn (ModeCotisation $a, ModeCotisation $b) => strcmp($a->getMembre()->getNom(), $b->getMembre()->getNom()));

        // Toutes les opérations (cotisations et amendes) des séances de cette campagne.
        $operations = $this->em->createQuery(
            'SELECT o FROM ' . Operation::class . ' o JOIN o.seance s WHERE s.campagne = :campagne'
        )->setParameter('campagne', $campagne)->getResult();

        // On range les montants par séance et par membre.
        $cotisations = [];
        $amendes = [];
        foreach ($operations as $operation) {
            $s = $operation->getSeance()->getId();
            $m = $operation->getMembre()->getId();
            $cotisations[$s][$m] = ($cotisations[$s][$m] ?? 0.0) + $operation->montantCotise();
            $amendes[$s][$m] = ($amendes[$s][$m] ?? 0.0) + $operation->montantAmende();
        }

        $cumuls = array_fill_keys(array_keys($membres), 0.0);
        $totauxAmendes = array_fill_keys(array_keys($membres), 0.0);
        $tableaux = [];
        $totalRecouvre = 0.0;
        $totalAmendes = 0.0;

        foreach ($seances as $seance) {
            $lignes = [];
            $cotiseSeance = 0.0;
            $amendeSeance = 0.0;

            foreach ($membres as $membreId => $mode) {
                $cotisation = $cotisations[$seance->getId()][$membreId] ?? 0.0;
                $amende = $amendes[$seance->getId()][$membreId] ?? 0.0;
                $cumuls[$membreId] += $cotisation;
                $totauxAmendes[$membreId] += $amende;

                $lignes[] = [
                    'membre' => $mode->getMembre(),
                    'cotisation' => $cotisation,
                    'amende' => $amende,
                    'cumul' => $cumuls[$membreId],
                ];
                $cotiseSeance += $cotisation;
                $amendeSeance += $amende;
            }

            $tableaux[] = [
                'seance' => $seance,
                'lignes' => $lignes,
                'totalCotisations' => $cotiseSeance,
                'totalAmendes' => $amendeSeance,
            ];
            $totalRecouvre += $cotiseSeance;
            $totalAmendes += $amendeSeance;
        }

        // Récapitulatif par membre sur toute la campagne.
        $recapitulatif = [];
        foreach ($membres as $membreId => $mode) {
            $recapitulatif[] = [
                'membre' => $mode->getMembre(),
                'cotise' => $cumuls[$membreId],
                'amendes' => $totauxAmendes[$membreId],
            ];
        }

        return $this->render('campagne/show.html.twig', [
            'campagne' => $campagne,
            'modes' => $membres,
            'tableaux' => $tableaux,
            'recapitulatif' => $recapitulatif,
            'totalRecouvre' => $totalRecouvre,
            'totalAmendes' => $totalAmendes,
        ]);
    }
}