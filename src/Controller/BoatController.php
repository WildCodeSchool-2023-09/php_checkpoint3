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
    public function moveBoat(int $x, int $y, BoatRepository $boatRepository, EntityManagerInterface $entityManager): Response 
    {
        $boat = $boatRepository->findOneBy([]);
        
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        if ($this->mapManager->checkTreasure($boat)) {
            $this->addFlash('success', 'Féliciations ! Tu as trouvé le trésor !');
        }

        $entityManager->flush();
        
        return $this->redirectToRoute('map');
    }

    #[Route('/direction/{direction}', name: 'moveDirection')]
    public function moveDirection(string $direction, BoatRepository $boatRepository, EntityManagerInterface $entityManager): Response 
    {
        $boat = $boatRepository->findOneBy([]);

        if (!$boat) {
            throw $this->createNotFoundException('No boat found');
        }

        $newX = $boat->getCoordX();
        $newY = $boat->getCoordY();

        switch ($direction) {
            case 'N':
                $newY -= 1;
                break;
            case 'S':
                $newY += 1;
                break;
            case 'E':
                $newX += 1;
                break;
            case 'W':
                $newX -= 1;
                break;
            default:
                throw $this->createNotFoundException('Invalid direction');
        }

        if (!$this->mapManager->tileExists($newX, $newY)) {
            $this->addFlash('danger', 'Le bateau ne peut pas aller dans cette direction !!!');
            return $this->redirectToRoute('map');
        }

        $boat->setCoordX($newX);
        $boat->setCoordY($newY);

        if ($this->mapManager->checkTreasure($boat)) {
            $this->addFlash('success', 'Féliciations ! Tu as trouvé le trésor !!!');
        }

        $entityManager->flush();
    
        return $this->redirectToRoute('map');
    }
}
