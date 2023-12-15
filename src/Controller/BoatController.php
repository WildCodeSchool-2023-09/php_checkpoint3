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

    #[Route('/direction/{direction}', name: 'moveDirection')]
    public function moveDirection(string $direction,
                                  BoatRepository $boatRepository,
                                  EntityManagerInterface $entityManager,
                                  MapManager $mapManager): Response
    {
        $boat = $boatRepository->findOneBy([]);

        $x = $boat->getCoordX();
        $y = $boat->getCoordY();
        switch($direction) {
            case 'N' :
                $boat->setCoordY($y - 1);
                break;
            case 'S' :
                $boat->setCoordY($y + 1);
                break;
            case 'W' :
                $boat->setCoordX($x - 1);
                break;
            case 'E' :
                $boat->setCoordX($x + 1);
                break;
        }
        if($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY())) {
            $entityManager->persist($boat);
            $entityManager->flush();
        }else {
            $this->addFlash('danger', 'Pas par là moussailon c\'est le territoire des Krakens');
        }
        return $this->redirectToRoute('map');
    }

}
