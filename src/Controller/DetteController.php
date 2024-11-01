<?php

namespace App\Controller;

use App\Entity\Client; 
use App\Repository\ClientRepository; 
use App\Repository\DetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Request;



class DetteController extends AbstractController
{
    #[Route('/dette/client/{id}', name: 'dette_list_by_client')]
public function listByClient(int $id, ClientRepository $clientRepository, DetteRepository $detteRepository, Request $request): Response
{
    $client = $clientRepository->find($id);
    
    if (!$client) {
        throw $this->createNotFoundException('Client non trouvé');
    }

    $statut = $request->query->get('statut', null);

    $dettes = $detteRepository->findBy(['client' => $client]);

    if ($statut !== null) {
        $dettes = array_filter($dettes, function ($dette) use ($statut) {
            return $dette->getStatut() === (bool)$statut;
        });
    }

    $totalDu = array_reduce($dettes, function($total, $dette) {
        return $total + $dette->getMontantRestant();
    }, 0);

    return $this->render('dette/list.html.twig', [
        'client' => $client,
        'dettes' => $dettes,
        'totalDu' => $totalDu,
        'statut' => $statut,
    ]);
}

    
}
