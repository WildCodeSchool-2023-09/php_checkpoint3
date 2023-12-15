<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MapManager;

#[Route('/boat')]
class BoatController extends AbstractController
{
    #[Route('/move/{x<\d+>}/{y<\d+>}', name: 'moveBoat')]
    public function moveBoat(
        int                    $x,
        int                    $y,
        BoatRepository         $boatRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $boat = $boatRepository->findOneBy([]);

        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $entityManager->flush();

        return $this->redirectToRoute('map');
    }

    #[Route('/boat/{direction}', name: 'moveDirection')]
    public function moveDirection(
        string $direction,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        MapManager $mapManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);
        $currentX = $boat->getCoordX();
        $currentY = $boat->getCoordY();

        $moveResult = $mapManager->isValidMove($currentX, $currentY, $direction);

        if ($moveResult === Response::HTTP_OK) {
            // Déplacement valide, mettez à jour les coordonnées du bateau
            $boat->setCoordX($currentX + ($direction === 'E' ? 1 : ($direction === 'W' ? -1 : 0)));
            $boat->setCoordY($currentY + ($direction === 'S' ? 1 : ($direction === 'N' ? -1 : 0)));

            $entityManager->flush();
        } else {
            // Déplacement invalide, affichez un message d'erreur flash
            $this->addFlash('danger', 'Attention ! Tu ne peux pas sortir de la carte!');
        }

        return $this->redirectToRoute('map');
    }

}