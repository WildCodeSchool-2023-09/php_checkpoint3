<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MapManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/boat')]
class BoatController extends AbstractController
{
    private $mapManager;

    public function __construct(MapManager $mapManager)
    {
        $this->mapManager = $mapManager;
    }

    #[Route('/move/{x<\d+>}/{y<\d+>}', name: 'moveBoat')]
    public function moveBoat(
        int $x,
        int $y,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager
    ): Response {
        // Sélectionne le bateau
        $boat = $boatRepository->findOneBy([]);

        // Coordonnées du bateau mises à jour avec les nouvelles variables
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        // Enregistre les modifications en BDD
        $entityManager->flush();

        // Redirige vers la route 'map' après le déplacement du bateau
        return $this->redirectToRoute('map');
    }

    #[Route('/direction/{direction}', name: 'moveDirection', requirements: ['direction' => 'N|S|E|W'])]
    public function moveDirection(
        string $direction,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);

        // Déplacement du bateau
        switch ($direction) {
            case 'N':
                $newY = $boat->getCoordY() - 1;
                $newX = $boat->getCoordX();
                break;
            case 'S':
                $newY = $boat->getCoordY() + 1;
                $newX = $boat->getCoordX();
                break;
            case 'E':
                $newX = $boat->getCoordX() + 1;
                $newY = $boat->getCoordY();
                break;
            case 'W':
                $newX = $boat->getCoordX() - 1;
                $newY = $boat->getCoordY();
                break;
            default:
                throw new \InvalidArgumentException("ERROR");
        }

        // Vérifie si la tuile de destination existe
        if (!$this->mapManager->tileExist($newX, $newY)) {
            $this->addFlash('error!!!!');
            return $this->redirectToRoute('map');
        }

        // Déplace le bateau seulement si la tuile existe
        $boat->setCoordX($newX);
        $boat->setCoordY($newY);
        $entityManager->flush();

        return $this->redirectToRoute('map');
    }

    // Rajout de boutons qui ne marchent absolument pas pour déplacer le bateau
    #[Route('/move-buttons', name: 'moveButtons')]
    public function moveButtons(): Response
    {
        return $this->render('mapbuttons.html.twig');
    }
}
