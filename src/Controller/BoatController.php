<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use App\Service\MapManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Boat;

#[Route('/boat')]
class BoatController extends AbstractController
{
    private Boat $boat;

    public function __construct(
        private BoatRepository $boatRepository,
        private EntityManagerInterface $entityManager
    )
    {
        $this->boat = $this->boatRepository->findOneBy([]);
    }

    #[Route('/move/{x<\d+>}/{y<\d+>}', name: 'moveBoat')]
    public function moveBoat(int $x, int $y): Response {
        $this->boat->setCoordX($x);
        $this->boat->setCoordY($y);

        $this->entityManager->flush();
        
        return $this->redirectToRoute('map');
    }

    #[Route('/direction/{dir}', name: 'direction' , requirements: ['dir' => 'N|S|E|W'])]
    public function moveDirection(string $dir, MapManager $mapManager)
    {
        $y = $this->boat->getCoordY();
        $x = $this->boat->getCoordX();

        if ($dir === 'N') {
            $y -= 1;
        } elseif ($dir === 'S') {
            $y += 1;
        } elseif ($dir === 'E') {
            $x += 1;
        } elseif ($dir === 'W') {
            $x -= 1;
        }

        if ($mapManager->tileExists($x, $y)) {
            $this->boat->setCoordY($y);
            $this->boat->setCoordX($x);
            $this->entityManager->flush();
        } else {
            $this->addFlash('danger', "Not Found !! ");
        }



        return $this->redirectToRoute('map');
    }
}
