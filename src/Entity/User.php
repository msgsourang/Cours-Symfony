<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface; 
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $surname = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 25, unique: true)]
    private ?string $login = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'compte', cascade: ['persist', 'remove'])]
    private ?Client $client = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Client $relation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        // unset the owning side of the relation if necessary
        if ($client === null && $this->client !== null) {
            $this->client->setCompte(null);
        }

        // set the owning side of the relation if necessary
        if ($client !== null && $client->getCompte() !== $this) {
            $client->setCompte($this);
        }

        $this->client = $client;

        return $this;
    }

    public function getRelation(): ?Client
    {
        return $this->relation;
    }

    public function setRelation(?Client $relation): static
    {
        $this->relation = $relation;
        return $this;
    }

    // Méthode requise par l'interface PasswordAuthenticatedUserInterface
    public function getUserIdentifier(): string
    {
        return $this->login; // Utiliser l'attribut login comme identifiant
    }

    // Méthode requise par l'interface UserInterface
    public function getRoles(): array
    {
        return ['ROLE_USER']; // Définir les rôles appropriés ici
    }

    public function getSalt(): ?string
    {
        return null; // Pas nécessaire si vous utilisez un algorithme de hachage moderne
    }

    // Méthode corrigée pour respecter l'interface UserInterface
    public function eraseCredentials(): void
    {
        // Si vous stockez des données sensibles, effacez-les ici
    }
}
