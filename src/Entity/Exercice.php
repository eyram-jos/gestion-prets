<?php

namespace App\Entity;

use App\Statut\StatutExercice;
use App\Traits\DateTimeTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Exercice
{
    use DateTimeTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateLimitePrets = null;

    // Rétribution, en pourcentage.
    #[ORM\Column]
    private ?float $retribution = null;

    // On enregistre le code du statut ; getStatut() le transforme en objet.
    #[ORM\Column(length: 20)]
    private string $statut = StatutExercice::EN_COURS;

    #[ORM\ManyToOne(targetEntity: Groupement::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupement $groupement = null;

    public function getId(): ?int { return $this->id; }

    public function getDateDebut(): ?\DateTimeImmutable { return $this->dateDebut; }
    public function setDateDebut(\DateTimeImmutable $dateDebut): static { $this->dateDebut = $dateDebut; return $this; }

    public function getDateFin(): ?\DateTimeImmutable { return $this->dateFin; }
    public function setDateFin(\DateTimeImmutable $dateFin): static { $this->dateFin = $dateFin; return $this; }

    public function getDateLimitePrets(): ?\DateTimeImmutable { return $this->dateLimitePrets; }
    public function setDateLimitePrets(\DateTimeImmutable $dateLimitePrets): static { $this->dateLimitePrets = $dateLimitePrets; return $this; }

    public function getRetribution(): ?float { return $this->retribution; }
    public function setRetribution(float $retribution): static { $this->retribution = $retribution; return $this; }

    public function getStatut(): StatutExercice { return StatutExercice::depuisCode($this->statut); }
    public function setStatut(StatutExercice $statut): static { $this->statut = $statut->code(); return $this; }

    public function getGroupement(): ?Groupement { return $this->groupement; }
    public function setGroupement(Groupement $groupement): static { $this->groupement = $groupement; return $this; }
}