<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SkillsRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: SkillsRepository::class)]
class Skills
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    private ?User $createdBy = null;

    #[ORM\OneToMany(mappedBy: 'skillId', targetEntity: EmployeeSkills::class)]
    private Collection $employeeSkills;

    public function __construct()
    {
        $this->employeeSkills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, EmployeeSkills>
     */
    public function getEmployeeSkills(): Collection
    {
        return $this->employeeSkills;
    }

    public function addEmployeeSkill(EmployeeSkills $employeeSkill): static
    {
        if (!$this->employeeSkills->contains($employeeSkill)) {
            $this->employeeSkills->add($employeeSkill);
            $employeeSkill->setSkillId($this);
        }

        return $this;
    }

    public function removeEmployeeSkill(EmployeeSkills $employeeSkill): static
    {
        if ($this->employeeSkills->removeElement($employeeSkill)) {
            // set the owning side to null (unless already changed)
            if ($employeeSkill->getSkillId() === $this) {
                $employeeSkill->setSkillId(null);
            }
        }

        return $this;
    }
}
