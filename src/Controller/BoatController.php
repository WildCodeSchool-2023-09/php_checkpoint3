<?php

namespace App\Controller;

use App\Entity\Boat;
use App\Form\BoatType;
use App\Service\MapManager;
use App\Repository\BoatRepository;
use Doctrine\Migrations\Version\Direction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
 
     
      
    #[Route('/boat/direction', name: 'moveDirection')]
     public function moveDirection(Direction $direction, BoatRepository $boatRepository, EntityManagerInterface $em, MapManager $mapManager): Response
    {
        $boat = $boatRepository->findOneBy([]);

        $x = $boat->getCoordX();
        $y = $boat->getCoordY();
        switch($direction) {
            case 'N' :
                $boat->setCoordY($y - 1);
                break;
            case 'S' :
                $boat->setCoordY($y + 1);
                break;
            case 'W' :
                $boat->setCoordX($x - 1);
                break;
            case 'E' :
                $boat->setCoordX($x + 1);
                break;
            default :
                //404
        }

    if($mapManager->tileExists($boat->getCoordX(), $boat->getCoordY())) {
        $em->persist($boat);
        $em->flush();
    } else {
        $this->addFlash('danger', 'You are leaving the map !');
    }     
        return $this->redirectToRoute('map');
    }
    
    #[Route('/new', name: 'new')]
    public function new(): Response
    {
        $boat = new Boat();

        // Create the form, linked with $category
        $form = $this->createForm(BoatType::class, $boat);
        
        // Render the form

        return $this->render('boat/new.html.twig', [
            'form' => $form,
        ]);
    }

    

}
