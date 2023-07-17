<?php

namespace App\Entity;

use App\Repository\CompanySubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: CompanySubscriptionRepository::class)]
class CompanySubscription
{
    public const PLAN_STATUS = [
        "EXPIRED" => "Expired",
        "CURRENT" => "Current",
        "UPCOMING" => "Upcoming"
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'companySubscriptions')]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'companySubscriptions')]
    private ?Subscription $subscription = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    use TimestampableEntity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getcompany(): ?Company
    {
        return $this->company;
    }

    public function setcompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getsubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setsubscription(?Subscription $subscription): static
    {
        $this->subscription = $subscription;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        if (!in_array($status, self::PLAN_STATUS))
            throw new \InvalidArgumentException("Invalid value passed!");
            
        $this->status = $status;

        return $this;
    }
}
