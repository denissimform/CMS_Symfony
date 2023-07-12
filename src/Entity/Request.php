<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RequestRepository;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $reason = null;

    #[ORM\Column]
    private ?bool $isApproved = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $requestAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $approvedAt = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    private ?Company $companyId = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    private ?User $fromId = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    private ?Tasks $taskId = null;

    #[ORM\ManyToOne(inversedBy: 'forwardToRequests')]
    private ?User $forwardTo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): static
    {
        $this->reason = $reason;

        return $this;
    }

    public function isIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): static
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getRequestAt(): ?\DateTimeImmutable
    {
        return $this->requestAt;
    }

    public function setRequestAt(\DateTimeImmutable $requestAt): static
    {
        $this->requestAt = $requestAt;

        return $this;
    }

    public function getApprovedAt(): ?\DateTimeImmutable
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(\DateTimeImmutable $approvedAt): static
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }

    public function getCompanyId(): ?Company
    {
        return $this->companyId;
    }

    public function setCompanyId(?Company $companyId): static
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function getFromId(): ?User
    {
        return $this->fromId;
    }

    public function setFromId(?User $fromId): static
    {
        $this->fromId = $fromId;

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

    public function getForwardTo(): ?User
    {
        return $this->forwardTo;
    }

    public function setForwardTo(?User $forwardTo): static
    {
        $this->forwardTo = $forwardTo;

        return $this;
    }
}
