<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $criteria_dept = null;

    #[ORM\Column]
    private ?int $criteria_user = null;

    #[ORM\Column]
    private ?int $criteria_storage = null;

    #[ORM\Column(length: 30)]
    private ?string $duration = null;

    #[ORM\Column]
    private ?int $price = null;

    use TimestampableEntity;

    #[ORM\ManyToMany(targetEntity: Company::class, inversedBy: 'subscriptions')]
    private Collection $companyId;

    #[ORM\OneToMany(mappedBy: 'subscriptionId', targetEntity: CompanySubscription::class)]
    private Collection $companySubscriptions;

    #[ORM\Column]
    private ?bool $isActive = null;

    public function __construct()
    {
        $this->companyId = new ArrayCollection();
        $this->companySubscriptions = new ArrayCollection();
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

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Company>
     */
    public function getCompanyId(): Collection
    {
        return $this->companyId;
    }

    public function addCompanyId(Company $companyId): static
    {
        if (!$this->companyId->contains($companyId)) {
            $this->companyId->add($companyId);
        }

        return $this;
    }

    public function removeCompanyId(Company $companyId): static
    {
        $this->companyId->removeElement($companyId);

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
}
