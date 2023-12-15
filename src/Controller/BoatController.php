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

    private $mapManager;

    public function __construct(MapManager $mapManager)
    {
        $this->mapManager = $mapManager;
    }

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

    #[Route('/direction/{direction} ', name: 'moveDirection')]
public function moveDirection(string $direction,
 BoatRepository $boatRepository, EntityManagerInterface $entityManager): Response
    {
        $boat = $boatRepository->findOneBy([]);

        switch ($direction) {
            case 'N':
                $boat->setCoordY($boat->getCoordY() - 1);
                break;
            case 'S':
                $boat->setCoordY($boat->getCoordY() + 1);
                break;
            case 'E':
                $boat->setCoordX($boat->getCoordX() + 1);
                break;
            case 'W':
                $boat->setCoordX($boat->getCoordX() - 1);
                break;
            default:
                throw $this->createNotFoundException('Invalid direction');
        }

        // Check if the new tile exists
        if ($this->mapManager->tileExists($newX ?? $boat->getCoordX(), $newY ?? $boat->getCoordY())) {
            if (isset($newX)) {
                $boat->setCoordX($newX);
            }
            if (isset($newY)) {
                $boat->setCoordY($newY);
            }

            $entityManager->flush();

            return $this->redirectToRoute('map');
        } else {
          
            return $this->redirectToRoute('map');
        }
    }
}
