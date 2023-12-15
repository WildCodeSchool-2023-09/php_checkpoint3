<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use App\Service\MapManager;

class MapController extends AbstractController
{

    private $mapManager;

    public function __construct(MapManager $mapManager)
    {
        $this->mapManager = $mapManager;
    }


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
    public function start(BoatRepository $boatRepository): Response
    {
        // Récupérez le bateau
        $boat = $boatRepository->findOneBy([]);

        // Remettez les coordonnées du bateau à 0,0
        $boat->setCoordX(0);
        $boat->setCoordY(0);

        // Placez le trésor sur une île aléatoire
        $randomIslandTile = $this->mapManager->getRandomIsland();
        if ($randomIslandTile) {
            // Supprimez les anciens trésors de la carte
            $this->mapManager->removeTreasures();

            // Placez le trésor sur la nouvelle île
            $randomIslandTile->setHasTreasure(true);
        }

        // Mettez à jour la base de données
        $this->getDoctrine()->getManager()->flush();

        // Redirigez vers /map
        return $this->redirectToRoute('map');
    }
}
