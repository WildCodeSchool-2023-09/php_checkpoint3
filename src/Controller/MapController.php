<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;

class MapController extends AbstractController
{

    private Boat $boat;

    public function __construct(
        private BoatRepository $boatRepository,
        private MapManager $mapManager
    )
    {
        $this->boat = $this->boatRepository->findOneBy([]);
    }

    #[Route('/map', name: 'map')]
    public function displayMap(TileRepository $tileRepository): Response
    {
        $tiles = $tileRepository->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

//        $boat = $this->boatRepository->findOneBy([]);

        $tile = $tileRepository->findOneBy([
            'coordX' => $this->boat->getCoordX(),
            'coordY' => $this->boat->getCoordY()
        ]);

        if ($this->mapManager->checkTreasure($this->boat)) {
            $this->addFlash('success', 'TU ES RICHE !!!!!');
        }

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $this->boat,
            'tile' => $tile
        ]);
    }

    #[Route('/start', name: 'start')]
    public function start(EntityManagerInterface $entityManager)
    {
        $this->boat->setCoordY(0);
        $this->boat->setCoordX(0);

        $this->mapManager->resetIsland();
        $tile = $this->mapManager->getRandomIsland();
        $tile->setHasTreasure(true);
        $entityManager->flush();

        return $this->redirectToRoute('map');
    }
}
