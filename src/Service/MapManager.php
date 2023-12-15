<?php

namespace App\Service;

use App\Repository\TileRepository;

class MapManager
{
    private $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function getRandomIsland()
    {
        // toutes les tiles disponisbles
        $islandTiles = $this->tileRepository->findBy(['type' => 'island']);
        // tiles disponibles
        if (empty($islandTiles)) {
            throw new \RuntimeException("No island tiles found.");
        }
        // tiles aléatoire 
        $randomIslandTile = $islandTiles[array_rand($islandTiles)];

        return $randomIslandTile;
    }
}
