<?php

namespace App\Entity;

use App\Repository\DetteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetteRepository::class)]
class Dette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column]
    private ?float $montantverser = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $date = null; 

    #[ORM\ManyToOne(inversedBy: 'dettes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    #[ORM\Column(type: 'boolean')]
    private bool $statut; // true = Solde, false = Non Solde

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getMontantVerser(): ?float
    {
        return $this->montantverser;
    }

    public function setMontantVerser(float $montantverser): static
    {
        $this->montantverser = $montantverser;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable 
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getMontantRestant(): ?float
    {
        return $this->montant - ($this->montantverser ?? 0);
    }

    public function isSolde(): bool
    {
        return $this->statut;
    }
    public function getStatut(): bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;
        return $this;
    }
}
