<?php

namespace App\Controller;

use App\Repository\BoatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boat', name: 'boat_')]
class BoatController extends AbstractController
{
    #[Route('/move/{x<\d+>}/{y<\d+>}', name: 'moveBoat')]
    public function moveBoat(
        int                    $x,
        int                    $y,
        BoatRepository         $boatRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $boat = $boatRepository->findOneBy([]);

        $boat->setCoordX($x);
        $boat->setCoordY($y);

        $entityManager->flush();

        return $this->redirectToRoute('map');

    }
}

//
//#[Route('/direction/{x<\d+>}/{y<\d+>}', name: 'moveDirection')]
//public function moveDirection($d); {}
 //   $y = 0;


//

//return $this->render('boat/direction.html.twig', [
//'d' => $d,



// afterwards redirect to map






