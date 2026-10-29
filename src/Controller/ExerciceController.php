<?php

namespace App\Controller;

use App\Entity\Campagne;
use App\Entity\Exercice;
use App\Entity\Pret;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExerciceController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    // Un exercice : ses campagnes, ses prêts et les intérêts à verser dans la caisse sociale.
    #[Route('/exercices/{id}', name: 'exercice_show', requirements: ['id' => '\d+'])]
    public function show(Exercice $exercice): Response
    {
        $campagnes = $this->em->getRepository(Campagne::class)
            ->findBy(['exercice' => $exercice], ['dateDebut' => 'ASC']);
        $prets = $this->em->getRepository(Pret::class)
            ->findBy(['exercice' => $exercice], ['dateDebut' => 'ASC']);

        $totalPrets = 0.0;
        $totalInterets = 0.0;
        foreach ($prets as $pret) {
            $totalPrets += $pret->getMontant();
            $totalInterets += $pret->montantInterets();
        }

        return $this->render('exercice/show.html.twig', [
            'exercice' => $exercice,
            'campagnes' => $campagnes,
            'prets' => $prets,
            'totalPrets' => $totalPrets,
            'totalInterets' => $totalInterets,
        ]);
    }
}