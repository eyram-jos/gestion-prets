<?php

namespace App\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait DateTimeTrait
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\PrePersist]
    public function initialiserDates(): void
    {
        $this->createAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function mettreAJourDate(): void
    {
        $this->updateAt = new \DateTimeImmutable();
    }

    public function getCreateAt(): ?\DateTimeImmutable { return $this->createAt; }
    public function getUpdateAt(): ?\DateTimeImmutable { return $this->updateAt; }
}