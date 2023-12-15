<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MapManager;
use App\Entity\Boat;

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
    

    #[Route('/direction/{move}', name: 'moveDirection')]
    public function moveDirection(
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager,
        string $move)
    {
        $boat = $boatRepository->findOneBy([]);

        $direction = [
            'x' => $boat->getCoordX(),
            'y' => $boat->getCoordY()
        ];

        if ($move === 'S') {
            $direction['y'] += 1;
        } elseif ($move === 'N') {
            $direction['y'] -= 1;
        } elseif ($move === 'W') {
            $direction['x'] -= 1;
        } elseif ($move === 'E') {
            $direction['x'] += 1;
        } 

        if ($direction['x'] < 0 || $direction['x'] > 11 || $direction['y'] < 0 || $direction['y'] > 11.5) {
            throw new \Exception('Tu sors de la carte !!!');
        }


        $boat->setCoordX($direction['x']);
        $boat->setCoordY($direction['y']);

        $entityManager->flush();

        return $this->redirectToRoute('map');

        return $this->render('map/index.html.twig', [
            'boat' => $boat,
            'direction' => $direction,
        ]);


    }
}
