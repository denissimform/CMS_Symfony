<?php

namespace App\Entity;

use App\Enum\PaymentType;
use App\Enum\ProjectStatus;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use InvalidArgumentException;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    use TimestampableEntity;

    public const PAYMENT_TYPE = ['Fixed Cost', 'Hourly'];
    public const PROJECT_STATUS = ["In Communication", "Accepted", "Rejected", "Initialized", "Completed"];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 90)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $expectedEndDate = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $paymentType = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $amc_support = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?Client $clientId = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    private ?User $createdBy = null;

    #[ORM\OneToMany(mappedBy: 'projectId', targetEntity: Tasks::class)]
    private Collection $tasks;

    #[ORM\OneToMany(mappedBy: 'projectId', targetEntity: Timesheets::class)]
    private Collection $timesheets;

    #[ORM\OneToMany(mappedBy: 'projectId', targetEntity: TimelineProject::class)]
    private Collection $timelineProjects;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->timesheets = new ArrayCollection();
        $this->timelineProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        if (!in_array($status, self::PROJECT_STATUS))
            throw new InvalidArgumentException("Invalid payment type");

        $this->status = $status;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getExpectedEndDate(): ?\DateTimeInterface
    {
        return $this->expectedEndDate;
    }

    public function setExpectedEndDate(\DateTimeInterface $expectedEndDate): static
    {
        $this->expectedEndDate = $expectedEndDate;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(?string $paymentType): static
    {
        if (!in_array($paymentType, self::PAYMENT_TYPE))
            throw new InvalidArgumentException("Invalid payment type");

        $this->paymentType = $paymentType;

        return $this;
    }

    public function getAmcSupport(): ?\DateTimeInterface
    {
        return $this->amc_support;
    }

    public function setAmcSupport(\DateTimeInterface $amc_support): static
    {
        $this->amc_support = $amc_support;

        return $this;
    }

    public function getClientId(): ?Client
    {
        return $this->clientId;
    }

    public function setClientId(?Client $clientId): static
    {
        $this->clientId = $clientId;

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
     * @return Collection<int, Tasks>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProjectId($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProjectId() === $this) {
                $task->setProjectId(null);
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
            $timesheet->setProjectId($this);
        }

        return $this;
    }

    public function removeTimesheet(Timesheets $timesheet): static
    {
        if ($this->timesheets->removeElement($timesheet)) {
            // set the owning side to null (unless already changed)
            if ($timesheet->getProjectId() === $this) {
                $timesheet->setProjectId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TimelineProject>
     */
    public function getTimelineProjects(): Collection
    {
        return $this->timelineProjects;
    }

    public function addTimelineProject(TimelineProject $timelineProject): static
    {
        if (!$this->timelineProjects->contains($timelineProject)) {
            $this->timelineProjects->add($timelineProject);
            $timelineProject->setProjectId($this);
        }

        return $this;
    }

    public function removeTimelineProject(TimelineProject $timelineProject): static
    {
        if ($this->timelineProjects->removeElement($timelineProject)) {
            // set the owning side to null (unless already changed)
            if ($timelineProject->getProjectId() === $this) {
                $timelineProject->setProjectId(null);
            }
        }

        return $this;
    }
}
