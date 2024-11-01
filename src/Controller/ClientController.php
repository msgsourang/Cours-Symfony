<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User; 
use App\Repository\ClientRepository;
use App\Form\ClientWithAccountType;
use App\Repository\DetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/client-list', name: 'client.index')]
    public function list(ClientRepository $clientRepo, PaginatorInterface $paginator, Request $request): Response
    {
        $surname = $request->query->get('surname', '');
        $telephone = $request->query->get('telephone', '');
        $hasAccount = $request->query->get('hasAccount', null) !== null ? (bool)$request->query->get('hasAccount') : null;

        $query = $clientRepo->findByFilters($surname, $telephone, $hasAccount);

        $clients = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), 
            8 
        );

        return $this->render('client/list.html.twig', [
            'clients' => $clients,
            'surname' => $surname,
            'telephone' => $telephone,
            'hasAccount' => $hasAccount, 
        ]);
    }

    #[Route('/client/{id}/dettes', name: 'dette_list_by_client')]
public function listDettes(Client $client, DetteRepository $detteRepo, Request $request): Response
{
    $statut = $request->query->get('statut', null); // Récupération de l'état de statut
    $dettes = $detteRepo->findBy(['client' => $client]);

    if ($statut !== null) {
        $dettes = array_filter($dettes, function ($dette) use ($statut) {
            return $dette->getStatut() === (bool)$statut; // Filtre par statut
        });
    }

    return $this->render('dette/list.html.twig', [
        'client' => $client,
        'dettes' => $dettes,
        'statut' => $statut, // Passez statut à la vue
    ]);
}

    #[Route('/client/create', name: 'client_create')]
    public function create(Request $request): Response
    {
        $client = new Client();
        $user = new User();
        
        $form = $this->createForm(ClientWithAccountType::class, $client);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $userData = $form->get('compte')->getData();
            $hasAccount = $form->get('hasAccount')->getData(); // Récupération de l'état de hasAccount

            if ($hasAccount) { // Vérification de la case à cocher
                $user->setPrenom($userData->getPrenom());
                $user->setLogin($userData->getLogin());
                $hashedPassword = $this->passwordHasher->hashPassword($user, $userData->getPassword());
                $user->setPassword($hashedPassword);
                $client->setCompte($user);
                
                $this->entityManager->persist($user);
            }

            $this->entityManager->persist($client);
            $this->entityManager->flush();

            return $this->redirectToRoute('client.index');
        }
        
        return $this->render('client/create.html.twig', [
            'form' => $form->createView(),
            'hasAccount' => false, // Définit par défaut à false
        ]);
    }

    #[Route('/client/store', name: 'client_store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $client = new Client();
        $client->setSurname($request->request->get('surname'));
        $client->setPrenom($request->request->get('prenom'));
        $client->setTelephone($request->request->get('telephone'));
        $client->setAdresse($request->request->get('adresse'));

        $hasAccount = $request->request->getBoolean('hasAccount', false); // Utiliser getBoolean pour hasAccount

        if ($hasAccount) {
            $user = new User();
            $user->setSurname($request->request->get('surname'));
            $user->setLogin($request->request->get('login'));
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, $request->request->get('password'))
            );
            $user->setPrenom($request->request->get('prenom'));

            $user->setClient($client);
            $client->setCompte($user);
            
            $this->entityManager->persist($user);
        }

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $this->redirectToRoute('client.index');
    }

    #[Route('/client/new', name: 'client_new')]
    public function new(Request $request): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientWithAccountType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($client);
            $this->entityManager->flush();

            return $this->redirectToRoute('client.index');
        }

        return $this->render('client/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
