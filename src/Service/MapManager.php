<?php

namespace App\Service;

use App\Repository\TileRepository;
use App\Entity\Tile;
use App\Entity\Boat;
use Doctrine\ORM\EntityManagerInterface;

class MapManager
{
    private $tileRepository;
    private $entityManager;


    public function __construct(TileRepository $tileRepository, EntityManagerInterface $entityManager)
    {
        $this->tileRepository = $tileRepository;
        $this->entityManager = $entityManager;
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

        return $islandTiles[array_rand($islandTiles)];
    }

    public function removeTreasures(): void
    {
        $treasureTiles = $this->tileRepository->findBy(['hasTreasure' => true]);

        foreach ($treasureTiles as $tile) {
            $tile->setHasTreasure(false);
        }

        $this->entityManager->flush();
    }

    public function checkTreasure(Boat $boat): bool
    {
        // Récupérez la tuile actuelle du bateau
        $currentTile = $this->tileRepository->findOneBy(['coordX' => $boat->getCoordX(), 'coordY' => $boat->getCoordY()]);

        // Vérifiez si la tuile actuelle a un trésor
        return $currentTile !== null && $currentTile->isHasTreasure();
    }
}
