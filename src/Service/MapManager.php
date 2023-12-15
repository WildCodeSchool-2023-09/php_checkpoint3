<?php

namespace App\Service;

use App\Repository\TileRepository;
use App\Entity\Tile;
use App\Entity\Boat;

class MapManager
{
    private $tileRepository;

    public function __construct(TileRepository $tileRepository)
    {
        $this->tileRepository = $tileRepository;
    }

    public function tileExists(int $x, int $y): bool
    {
        $tile = $this->tileRepository->findOneBy(['coordinates' => [$x, $y]]);
        return $tile !== null;
    }
}

