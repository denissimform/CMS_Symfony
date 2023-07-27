<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Enum\EmployeeSkillLevel;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmployeeSkillsRepository;

#[ORM\Entity(repositoryClass: EmployeeSkillsRepository::class)]
class EmployeeSkills
{
    public const SKILL_LEVEL = ['Expert', 'Intermediate', 'Basic'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'employeeSkills')]
    private ?User $userId = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $level = null;

    #[ORM\ManyToOne()]
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

    public function setLevel(?string $level): static
    {
        if (!in_array($level, self::SKILL_LEVEL))
            throw new \InvalidArgumentException("Invalid value passed!");

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
