<?php

namespace App\Service;

use App\Entity\Boat;
use App\Entity\Tile;
use App\Repository\TileRepository;

class MapManager
{
    public function __construct(private TileRepository $tileRepository){

    }
    public function tileExists(int $x, int $y):bool
    {
        return $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]) !== null;
    }

    public function getRandomIsland(): Tile
    {
        $islandTiles = $this->tileRepository->findBy(['type' => 'island']);
        return $islandTiles[array_rand($islandTiles)];
    }

    public function checkTreasure(Boat $boat): bool
    {
        $treasureTile = $this->tileRepository->findOneBy(['hasTreasure' => true]);

        return $boat->isOn($treasureTile);
    }
}