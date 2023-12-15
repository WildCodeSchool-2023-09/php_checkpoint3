<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/direction/{direction}', name: 'moveDirection', requirements: ['direction' => '[NSEW]'])]
    public function moveDirection(
        BoatRepository         $boatRepository,
        EntityManagerInterface $entityManager,
        string                 $direction,

    ): Response
    {
        $boat = $boatRepository->findOneBy([]);

        if ($direction === 'N') {
            $boat->setCoordY($boat->getCoordY() - 1);
        } elseif ($direction === 'S') {
            $boat->setCoordY($boat->getCoordY() + 1);
        } elseif ($direction === 'E') {
            $boat->setCoordX($boat->getCoordX() + 1);
        } elseif ($direction === 'W') {
            $boat->setCoordX($boat->getCoordX() -1 );
        }
        if ($this->mapManager->tileExists($boat->getCoordX(), $boat->getCoordY())) {
            $entityManager->flush();
        }
        return $this->redirectToRoute('map');
    }
}