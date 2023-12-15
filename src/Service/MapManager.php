<?php

namespace App\Service;

use App\Entity\Tile;
use App\Repository\TileRepository;
use Doctrine\Persistence\ManagerRegistry;

class MapManager
{
    private TileRepository $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $x, int $y): bool
     {
         $tile = $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);

         return $tile !== null;
     }

     public function getRandomIsland(){
         $islandTiles = $this->tileRepository->findBy(['type' => 'island']);

         if (empty($islandTiles)) {
             return null;
         }

         $randomIslandTile = $islandTiles[array_rand($islandTiles)];

         return $randomIslandTile;
     }

     public function checkTreasure(Boat $boat): bool {

         $currentTile = $this->tileRepository->findOneBy([
             'coordX' => $boat->getCoordX(),
             'coordY' => $boat->getCoordY(),
         ]);

         return $currentTile !== null && $currentTile->isHasTreasure();
     }
}