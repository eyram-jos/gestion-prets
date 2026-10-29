<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 30)]
    private ?string $tel = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100)]
    private ?string $login = null;

    #[ORM\Column(length: 100)]
    private ?string $fonction = null;

    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\ManyToOne(targetEntity: Groupement::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Groupement $groupement = null;

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): static { $this->prenom = $prenom; return $this; }

    public function getTel(): ?string { return $this->tel; }
    public function setTel(string $tel): static { $this->tel = $tel; return $this; }

    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(string $adresse): static { $this->adresse = $adresse; return $this; }

    public function getLogin(): ?string { return $this->login; }
    public function setLogin(string $login): static { $this->login = $login; return $this; }

    public function getFonction(): ?string { return $this->fonction; }
    public function setFonction(string $fonction): static { $this->fonction = $fonction; return $this; }

    public function getRole(): ?Role { return $this->role; }
    public function setRole(Role $role): static { $this->role = $role; return $this; }

    public function getGroupement(): ?Groupement { return $this->groupement; }
    public function setGroupement(Groupement $groupement): static { $this->groupement = $groupement; return $this; }
}