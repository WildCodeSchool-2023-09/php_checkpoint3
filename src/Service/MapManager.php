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
        $type = $this->tileRepository->findBy(['type' => 'island']);
        $randomIslandTile = $type[array_rand($type)];

        return $randomIslandTile;
    }

    public function checkTreasure(Boat $boat): bool
    {
        $tile = $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);
        return $tile !== null && $tile->isHasTreasure();
    }
}