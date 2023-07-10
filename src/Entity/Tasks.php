<?php

namespace App\Entity;

use App\Repository\TasksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TasksRepository::class)]
class Tasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $type = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $priority = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $severity = [];

    #[ORM\Column(length: 90)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $status = [];

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?Project $projectId = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $createdBy = null;

    #[ORM\OneToMany(mappedBy: 'taskId', targetEntity: Request::class)]
    private Collection $requests;

    #[ORM\OneToMany(mappedBy: 'taskId', targetEntity: Timesheets::class)]
    private Collection $timesheets;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
        $this->timesheets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): array
    {
        return $this->type;
    }

    public function setType(array $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPriority(): array
    {
        return $this->priority;
    }

    public function setPriority(array $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getSeverity(): array
    {
        return $this->severity;
    }

    public function setSeverity(array $severity): static
    {
        $this->severity = $severity;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getStatus(): array
    {
        return $this->status;
    }

    public function setStatus(array $status): static
    {
        $this->status = $status;

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

    public function getProjectId(): ?Project
    {
        return $this->projectId;
    }

    public function setProjectId(?Project $projectId): static
    {
        $this->projectId = $projectId;

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
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setTaskId($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getTaskId() === $this) {
                $request->setTaskId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Timesheets>
     */
    public function getTimesheets(): Collection
    {
        return $this->timesheets;
    }

    public function addTimesheet(Timesheets $timesheet): static
    {
        if (!$this->timesheets->contains($timesheet)) {
            $this->timesheets->add($timesheet);
            $timesheet->setTaskId($this);
        }

        return $this;
    }

    public function removeTimesheet(Timesheets $timesheet): static
    {
        if ($this->timesheets->removeElement($timesheet)) {
            // set the owning side to null (unless already changed)
            if ($timesheet->getTaskId() === $this) {
                $timesheet->setTaskId(null);
            }
        }

        return $this;
    }
}
