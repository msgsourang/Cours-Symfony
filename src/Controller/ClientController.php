<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\DetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    #[Route('/client-list', name: 'client.index')]
    public function list(ClientRepository $clientRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $surname = $request->query->get('surname', '');
        $telephone = $request->query->get('telephone', '');

        $query = $clientRepo->findByFilters($surname, $telephone);

        $clients = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), 
            8 
        );

        return $this->render('client/list.html.twig', [
            'clients' => $clients,
            'surname' => $surname,
            'telephone' => $telephone,
        ]);
    }

    #[Route('/client/{id}/dettes', name: 'dette_list_by_client')]
    public function listDettes(Client $client, DetteRepository $detteRepo): Response
    {
        $dettes = $detteRepo->findBy(['client' => $client]);
    
        return $this->render('dette/list.html.twig', [
            'client' => $client,
            'dettes' => $dettes,
        ]);
    }

    #[Route('/client/create', name: 'client_create')]
    public function create(): Response
    {
        return $this->render('client/create.html.twig');
    }

    #[Route('/client/store', name: 'client_store', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): Response
    {
        $surname = $request->request->get('surname');
        $telephone = $request->request->get('telephone');
        $adresse = $request->request->get('adresse'); 

        $client = new Client();
        $client->setSurname($surname);
        $client->setTelephone($telephone);
        $client->setAdresse($adresse); 

        $em->persist($client);
        $em->flush();

        return $this->redirectToRoute('client.index');
    }
}
