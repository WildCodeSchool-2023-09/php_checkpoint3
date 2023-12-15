<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/direction/{direction}', name: 'moveDirectionBoat')]
    public function moveDirectionBoat($direction, BoatRepository $boatRepository,EntityManagerInterface $entityManager, MapManager $mapManager) : Response {

        $validDirection = ['N', 'S', 'E', 'W'];

        if(!in_array($direction, $validDirection )){
            throw $this->createNotFoundException('direction invalide');
        }

        $boat = $boatRepository->findOneBy([]);

        $x = $boat->getCoordX();
        $y = $boat->getCoordY();

        if($direction === 'N'){
            $y --;
        } elseif($direction === 'S'){
            $y ++;
        } elseif($direction === 'W'){
            $x --;
        } elseif($direction === 'E'){
            $x ++;
        } else {
            throw $this->createNotFoundException('direction invalide');
        }

        if(!$mapManager->tileExists($x, $y)) {
            $this->addFlash('message', 'NON, reviens tu vas te perdre');

            return $this->redirectToRoute('map', [], Response::HTTP_SEE_OTHER);
        }

        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $entityManager->flush();

        return $this->redirectToRoute('map');

    }
}
