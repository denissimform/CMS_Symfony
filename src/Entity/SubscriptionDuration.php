<?php

namespace App\Entity;

use App\Repository\SubscriptionDurationRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubscriptionDurationRepository::class)]
class SubscriptionDuration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('subscription:dt:read')]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups('subscription:dt:read')]
    private ?int $duration = null;

    #[ORM\Column(nullable: true)]
    #[Groups('subscription:dt:read')]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptionDurations')]
    private ?Subscription $subscriptionId = null;

    #[ORM\Column(nullable: true)]
    #[Groups('subscription:dt:read')]
    private ?bool $isActive = null;

    use TimestampableEntity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSubscriptionId(): ?Subscription
    {
        return $this->subscriptionId;
    }

    public function setSubscriptionId(?Subscription $subscriptionId): static
    {
        $this->subscriptionId = $subscriptionId;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
