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
        if($this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]) !== null){
            return true;
        }
        return false;
    }

    public function getRandomIsland(): Tile
    {
        $islandTiles = $this->tileRepository->findBy(['type' => 'island']);
        return $islandTiles[array_rand($islandTiles)];
    }

    public function checkTreasure(Boat $boat): bool
    {
        $treasureTile = $this->tileRepository->findOneBy(['hasTreasure' => true]);
        if ($boat->getCoordX() === $treasureTile->getCoordX() && $boat->getCoordY() === $treasureTile->getCoordY()){
            return true;
        }
        return false;
    }
}