<?php

namespace App\Dto;

// DTO d'entrée : les critères de la liste des groupements.
// Le formulaire de filtre remplit le téléphone et la ville ; le contrôleur renseigne la page.
class GroupementFiltreDto
{
    // Nombre de groupements affichés par page.
    public const PAR_PAGE = 2;

    private ?string $tel = null;
    private ?string $ville = null;
    private int $page = 1;

    public function getTel(): ?string { return $this->tel; }
    public function setTel(?string $tel): static { $this->tel = $tel; return $this; }

    public function getVille(): ?string { return $this->ville; }
    public function setVille(?string $ville): static { $this->ville = $ville; return $this; }

    public function getPage(): int { return $this->page; }
    public function setPage(int $page): static { $this->page = max(1, $page); return $this; }
}