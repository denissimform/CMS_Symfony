<?php

namespace App\Entity;

use App\Repository\EmployeeSkillsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeSkillsRepository::class)]
class EmployeeSkills
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'employeeSkills')]
    private ?User $userId = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $level = null;

    #[ORM\ManyToOne(inversedBy: 'employeeSkills')]
    private ?Skills $skillId = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getSkillId(): ?Skills
    {
        return $this->skillId;
    }

    public function setSkillId(?Skills $skillId): static
    {
        $this->skillId = $skillId;

        return $this;
    }
}
