<?php

namespace App\Controller;

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
    #[Route('/map', name: 'map')]
    public function displayMap(BoatRepository $boatRepository, TileRepository $tileRepository): Response
    {
        $tiles = $tileRepository->findAll();

        foreach ($tiles as $tile) {
            $map[$tile->getCoordX()][$tile->getCoordY()] = $tile;
        }

        $boat = $boatRepository->findOneBy([]);

        return $this->render('map/index.html.twig', [
            'map'  => $map ?? [],
            'boat' => $boat,
        ]);
    }

    #[Route('/start', name: 'start')]
    public function start(BoatRepository$boatRepository, EntityManagerInterface $entityManager, TileRepository $tileRepository, MapManager $mapManager): Response
    {
        //reset boat(0,0)
        $boat = $boatRepository->findOneBy([]);
        $boat->reset();
        //reset tiles
        $treasureTile = $tileRepository->findOneBy(['hasTreasure' => true]);
        $treasureTile?->setHasTreasure(false);
        //put treasure
        $mapManager->getRandomIsland()->setHasTreasure(true);
        //save
        $entityManager->flush();
        //redirect to map
        return $this->redirectToRoute('map');
    }
}
