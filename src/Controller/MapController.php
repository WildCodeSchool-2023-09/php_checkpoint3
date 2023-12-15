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

    #[Route('/start', name: 'startGame')]
    public function start(MapManager $mapManager, EntityManagerInterface
    $entityManager) : Response
    {
        $boat = $entityManager->getRepository(Boat::class)->findOneBy([]);

        $boat->setCoordX(0);
        $boat->setCoordY(0);

        $mapManager->placeTreasureOnRandomIsland();

        $entityManager->flush();

        return $this->redirectToRoute('map');

    }
}
