<?php

namespace App\Service;

use App\Entity\Tile;
use App\Repository\TileRepository;
use Doctrine\Persistence\ManagerRegistry;

class MapManager
{

    public function tileExists(int $x, int $y): bool
     {
         $tile = $this->tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);

         return $tile !== null;
     }
}