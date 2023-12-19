<?php

namespace App\Service;

use App\Entity\Boat;
use App\Entity\Tile;
use App\Repository\TileRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class MapManager
{
    public function __construct(
        private TileRepository $tileRepository,
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function tileExists(int $x, int $y): bool
    {
        return $x >= 0 && $x <= 5 && $y >= 0 && $y <= 11;
//        if ($x >= 0 && $x <= 5 && $y >= 0 && $y <= 11) {
//            return true;
//        }
//        return false;
    }

    public function resetIsland()
    {
        $tile = $this->tileRepository->findOneBy(['hasTreasure' => true]);
        if (isset($tile)) {
            $tile->setHasTreasure(false);
            $this->entityManager->flush();
        }
    }

    public function getRandomIsland(): Tile
    {
        $tiles = $this->tileRepository->findBy(['type' => 'island']);
        $index = array_rand($tiles);
        return $tiles[$index];
    }

    public function checkTreasure(Boat $boat): bool
    {
        $tile = $this->tileRepository->findOneBy([
            'coordX' => $boat->getCoordX(),
            'coordY' => $boat->getCoordY(),
            'hasTreasure' => true
        ]);
        return isset($tile);
    }
}