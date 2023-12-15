<?php

namespace App\Service;

use App\Repository\TileRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Tile;
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

    public function isValidMove(int $currentX, int $currentY, string $direction): int
    {
        $newX = $currentX;
        $newY = $currentY;

        switch ($direction) {
            case 'E':
                $newX += 1;
                break;
            case 'W':
                $newX -= 1;
                break;
            case 'N':
                $newY -= 1;
                break;
            case 'S':
                $newY += 1;
                break;
        }

        return $this->tileExists($newX, $newY) ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
    }

    public function getRandomIsland(): ?Tile
    {
        $islandTiles = $this->tileRepository->findAllIslandTiles();

        if (empty($islandTiles)) {
            return null; // Aucune tuile de l'île n'a été trouvée
        }

        $randomIndex = array_rand($islandTiles);
        $randomIslandTile = $islandTiles[$randomIndex];

        return $randomIslandTile;
    }

    public function placeTreasureOnRandomIsland(): void
    {
        // Supprimer tous les anciens trésors de la carte
        $this->tileRepository->updateAllTreasuresToFalse();

        // Choisir une tuile aléatoire qui est une île
        $randomIslandTile = $this->tileRepository->findOneRandomIslandTile();

        if ($randomIslandTile instanceof Tile) {
            // Placer le trésor sur l'île aléatoire
            $randomIslandTile->setHasTreasure(true);

            // Enregistrer les modifications
            $this->entityManager->flush();
        }
    }
}