<?php

namespace App\Service;

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
            'coordY' => $coordY,
        ]);
        return $tile != null;
    }
}