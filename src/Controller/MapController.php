<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tile;
use App\Repository\BoatRepository;
use App\Repository\TileRepository;
use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;

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

    #[Route('/start', name: 'start_game')]
    public function start(BoatRepository $boatRepository, MapManager $mapManager, EntityManagerInterface $entityManager, TileRepository $tileRepository): RedirectResponse
    {
        // Réinitialiser les coordonnées du bateau
        $boat = $boatRepository->findOneBy([]);
        if ($boat) {
            $boat->setCoordX(0);
            $boat->setCoordY(0);
            $entityManager->persist($boat);
        }

        // Réinitialiser le trésor sur les tuiles
        $tilesWithTreasure = $tileRepository->findBy(['hasTreasure' => true]);
        foreach ($tilesWithTreasure as $tile) {
            $tile->setHasTreasure(false);
            $entityManager->persist($tile);
        }

        // Placer le trésor sur une île aléatoire
        $randomIsland = $mapManager->getRandomIsland();
        if ($randomIsland) {
            $randomIsland->setHasTreasure(true);
            $entityManager->persist($randomIsland);
        }

        $entityManager->flush();

        // Rediriger vers /map
        return $this->redirectToRoute('map');
    }
}
