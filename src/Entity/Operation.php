<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap(['cotisation' => Cotisation::class, 'amende' => Amende::class])]
abstract class Operation
{
    // Chaque type d'opération indique la part qu'elle représente en cotisation et en amende.
    abstract public function montantCotise(): float;
    abstract public function montantAmende(): float;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(targetEntity: Membre::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Membre $membre = null;

    #[ORM\ManyToOne(targetEntity: Seance::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seance $seance = null;

    public function getId(): ?int { return $this->id; }

    public function getMontant(): ?float { return $this->montant; }
    public function setMontant(float $montant): static { $this->montant = $montant; return $this; }

    public function getMembre(): ?Membre { return $this->membre; }
    public function setMembre(Membre $membre): static { $this->membre = $membre; return $this; }

    public function getSeance(): ?Seance { return $this->seance; }
    public function setSeance(Seance $seance): static { $this->seance = $seance; return $this; }
}