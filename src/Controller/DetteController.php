<?php

namespace App\Controller;

use App\Entity\Client; 
use App\Repository\ClientRepository; 
use App\Repository\DetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetteController extends AbstractController
{
    #[Route('/dette/client/{id}', name: 'dette_list_by_client')]
    public function listByClient(int $id, ClientRepository $clientRepository, DetteRepository $detteRepository): Response
    {
        $client = $clientRepository->find($id);
        
        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }

        $dettes = $detteRepository->findBy(['client' => $client]);

        $totalDu = array_reduce($dettes, function($total, $dette) {
            return $total + $dette->getMontantRestant();
        }, 0);

        return $this->render('dette/list.html.twig', [
            'client' => $client, 
            'dettes' => $dettes,
            'totalDu' => $totalDu, 
        ]);
    }
}
