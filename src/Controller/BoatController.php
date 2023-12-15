<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use App\Repository\TileRepository;
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
        int $x,
        int $y,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);
        
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $entityManager->flush();
        
        return $this->redirectToRoute('map');
    }

    #[Route('/direction/{direction}', requirements: ['direction'=>'[NSEW]'], name: 'direction')]
    public function moveDirection(
        string $direction,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        MapManager $mapManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);
        $order = 'go' . $direction;
        $boat->$order();
        if($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY())){
            $entityManager->flush();
            if($mapManager->checkTreasure($boat)){
                $this->addFlash('success', 'You find the treasure ! Great job !');
            }

            return $this->redirectToRoute('map');
        }

        $this->addFlash('danger', 'The word is over'); //
        return $this->redirectToRoute('map');
    }
}
