<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boat')]
class BoatController extends AbstractController
{
    #[Route('/move/{x<\d+>}/{y<\d+>}', name: 'moveBoat')]
    public function moveBoat(
        int $x,
        int $y,
        BoatRepository $boatRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $boat = $boatRepository->findOneBy([]);
        
        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $entityManager->flush();
        
        return $this->redirectToRoute('map');
    }
      /**
     * @Route("/boat/direction/{direction}", name="move_direction", requirements={"direction"="[NSWE]"})
     */
    public function moveDirection(string $direction, BoatRepository $boatRepository, EntityManagerInterface $entityManager): Response {
        $boat = $boatRepository->findOneBy([]);



        if (!$boat) {
            throw $this->createNotFoundException('No boat found');

        // Get current boat coordinates
        $coordinates = $boat->getCoordinates();
        [$x, $y] = $coordinates;

        // Update coordinates based on direction
        switch ($direction) {
            case 'N':
                // Move north (increase Y)
                $y++;
                break;
            case 'S':
                // Move south (decrease Y)
                $y--;
                break;
            case 'E':
                // Move east (increase X)
                $x++;
                break;
            case 'W':
                // Move west (decrease X)
                $x--;
                break;
            // Handle invalid direction (Symfony will generate a 404 error)
            default:
                throw $this->createNotFoundException('Invalid direction');
        }

        // Update Boat coordinates
        $boat->setCoordinates([$x, $y]);

        
        $entityManager->flush();

        // Redirect to the map
        return $this->redirectToRoute('map');
    }
    
    }
}
