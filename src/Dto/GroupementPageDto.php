<?php

namespace App\Dto;

// DTO de sortie : une page de la liste des groupements, avec les informations de pagination.
class GroupementPageDto
{
    /**
     * @param GroupementLigneDto[] $lignes les groupements de la page
     */
    public function __construct(
        public readonly array $lignes,
        public readonly int $page,
        public readonly int $parPage,
        public readonly int $total,
    ) {
    }

    public function nbPages(): int
    {
        return max(1, (int) ceil($this->total / $this->parPage));
    }

    public function aPagePrecedente(): bool
    {
        return $this->page > 1;
    }

    public function aPageSuivante(): bool
    {
        return $this->page < $this->nbPages();
    }
}