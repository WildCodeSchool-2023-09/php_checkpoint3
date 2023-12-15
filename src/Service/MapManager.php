<?php

namespace App\Service;

use App\Entity\Tile;
use App\Repository\TileRepository;

class MapManager
{
    private TileRepository $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $coordX, int $coordY): bool
    {
        $tile = $this->tileRepository->findOneBy([
            'coordX' => $coordX,
            'coordY' => $coordY
        ]);
        return $tile != null;
    }

    public function getRandomIsland() : Tile
    {
        $islands = $this->tileRepository->findBy(
            ['type' => 'island']
        );
        $treasureIsland = array_rand($islands, 1);
        return $islands[$treasureIsland];
    }

    public function checkTreasure(Boat $boat): bool
    {
        $treasurePosition = $this->tileRepository->findOneBy(['hasTreasure' => true]);
        if ($boat->getCoordX() === $treasurePosition->getCoordX() && $boat->getCoordY() === $treasurePosition->getCoordY()) {
            return true;
        }
        return false;
    }
}