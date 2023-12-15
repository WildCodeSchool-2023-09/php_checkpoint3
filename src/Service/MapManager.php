<?php

namespace App\Service;

use App\Repository\TileRepository;
use App\Entity\Tile;

class MapManager
{
    private $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $x, int $y): bool
    {
        $tile = $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);

        return $tile !== null;
    }

    public function getRandomIsland(): ?Tile
    {
        $islandTiles = $this->tileRepository->findBy(['type' => 'island']);

     
        if (empty($islandTiles)) {
            return null;
        }

        $randomIslandTile = $islandTiles[array_rand($islandTiles)];

        return $randomIslandTile;
    }
}
