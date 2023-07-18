<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
#[UniqueEntity(
    fields: ['type'],
    message: 'This type is already in use on subscription.',
)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups('transactions:dt:read', 'subscription:dt:read')]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $criteria_dept = null;

    #[ORM\Column]
    private ?int $criteria_user = null;

    #[ORM\Column]
    private ?int $criteria_storage = null;

    use TimestampableEntity;

    #[ORM\OneToMany(mappedBy: 'subscriptionId', targetEntity: CompanySubscription::class)]
    private Collection $companySubscriptions;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'subscriptionId', targetEntity: SubscriptionDuration::class)]
    private Collection $subscriptionDurations;

    // public const PLAN_TYPE = ["silver", "gold", "premium"];
    public function __construct()
    {
        $this->companySubscriptions = new ArrayCollection();
        $this->subscriptionDurations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        // if (!in_array($type, self::PLAN_TYPE))
        //     throw new \InvalidArgumentException("Invalid value passed!");

        $this->type = $type;

        return $this;
    }

    public function getCriteriaDept(): ?int
    {
        return $this->criteria_dept;
    }

    public function setCriteriaDept(int $criteria_dept): static
    {
        $this->criteria_dept = $criteria_dept;

        return $this;
    }

    public function getCriteriaUser(): ?int
    {
        return $this->criteria_user;
    }

    public function setCriteriaUser(int $criteria_user): static
    {
        $this->criteria_user = $criteria_user;

        return $this;
    }

    public function getCriteriaStorage(): ?int
    {
        return $this->criteria_storage;
    }

    public function setCriteriaStorage(int $criteria_storage): static
    {
        $this->criteria_storage = $criteria_storage;

        return $this;
    }


    /**
     * @return Collection<int, CompanySubscription>
     */
    public function getCompanySubscriptions(): Collection
    {
        return $this->companySubscriptions;
    }

    public function addCompanySubscription(CompanySubscription $companySubscription): static
    {
        if (!$this->companySubscriptions->contains($companySubscription)) {
            $this->companySubscriptions->add($companySubscription);
            $companySubscription->setSubscriptionId($this);
        }

        return $this;
    }

    public function removeCompanySubscription(CompanySubscription $companySubscription): static
    {
        if ($this->companySubscriptions->removeElement($companySubscription)) {
            // set the owning side to null (unless already changed)
            if ($companySubscription->getSubscriptionId() === $this) {
                $companySubscription->setSubscriptionId(null);
            }
        }

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

    /**
     * @return Collection<int, SubscriptionDuration>
     */
    public function getSubscriptionDurations(): Collection
    {
        return $this->subscriptionDurations;
    }

    public function addSubscriptionDuration(SubscriptionDuration $subscriptionDuration): static
    {
        if (!$this->subscriptionDurations->contains($subscriptionDuration)) {
            $this->subscriptionDurations->add($subscriptionDuration);
            $subscriptionDuration->setSubscriptionId($this);
        }

        return $this;
    }

    public function removeSubscriptionDuration(SubscriptionDuration $subscriptionDuration): static
    {
        if ($this->subscriptionDurations->removeElement($subscriptionDuration)) {
            // set the owning side to null (unless already changed)
            if ($subscriptionDuration->getSubscriptionId() === $this) {
                $subscriptionDuration->setSubscriptionId(null);
            }
        }

        return $this;
    }
}
