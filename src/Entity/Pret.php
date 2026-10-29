<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['sans_interet' => PretSansInteret::class, 'avec_interet' => PretAvecInteret::class])]
abstract class Pret
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column]
    private ?int $dureeEnMois = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\ManyToOne(targetEntity: Membre::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Membre $membre = null;

    #[ORM\ManyToOne(targetEntity: Exercice::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Exercice $exercice = null;

    // Chaque classe enfant doit écrire sa propre version de cette méthode.
    abstract public function montantTotalARembourser(): float;

    // Les intérêts sont la différence entre le total à rembourser et le montant emprunté.
    public function montantInterets(): float
    {
        return $this->montantTotalARembourser() - ($this->montant ?? 0.0);
    }

    public function getId(): ?int { return $this->id; }

    public function getMontant(): ?float { return $this->montant; }
    public function setMontant(float $montant): static { $this->montant = $montant; return $this; }

    public function getDureeEnMois(): ?int { return $this->dureeEnMois; }
    public function setDureeEnMois(int $dureeEnMois): static { $this->dureeEnMois = $dureeEnMois; return $this; }

    public function getDateDebut(): ?\DateTimeImmutable { return $this->dateDebut; }
    public function setDateDebut(\DateTimeImmutable $dateDebut): static { $this->dateDebut = $dateDebut; return $this; }

    public function getMembre(): ?Membre { return $this->membre; }
    public function setMembre(Membre $membre): static { $this->membre = $membre; return $this; }

    public function getExercice(): ?Exercice { return $this->exercice; }
    public function setExercice(Exercice $exercice): static { $this->exercice = $exercice; return $this; }
}