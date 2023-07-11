<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TimesheetsRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TimesheetsRepository::class)]
class Timesheets
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $hoursWorked = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(inversedBy: 'timesheets')]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: 'timesheets')]
    private ?Project $projectId = null;

    #[ORM\ManyToOne(inversedBy: 'timesheets')]
    private ?Tasks $taskId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHoursWorked(): ?\DateTimeInterface
    {
        return $this->hoursWorked;
    }

    public function setHoursWorked(\DateTimeInterface $hoursWorked): static
    {
        $this->hoursWorked = $hoursWorked;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getProjectId(): ?Project
    {
        return $this->projectId;
    }

    public function setProjectId(?Project $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }

    public function getTaskId(): ?Tasks
    {
        return $this->taskId;
    }

    public function setTaskId(?Tasks $taskId): static
    {
        $this->taskId = $taskId;

        return $this;
    }
}
