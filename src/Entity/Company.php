<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[UniqueEntity(fields: ['name', 'isActive'], message: "This name is already in used!!")]
class Company
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $about = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $establishedAt = null;

    #[ORM\Column]
    private ?bool $isActive = true;

    #[ORM\OneToMany(mappedBy: 'companyId', targetEntity: Client::class)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'companyId', targetEntity: Department::class)]
    private Collection $departments;

    #[ORM\OneToMany(mappedBy: 'companyId', targetEntity: Request::class)]
    private Collection $requests;

    #[ORM\OneToMany(mappedBy: 'companyId', targetEntity: TimeLine::class)]
    private Collection $timeLines;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class)]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: Subscription::class, mappedBy: 'companyId')]
    private Collection $subscriptions;

    #[ORM\OneToMany(mappedBy: 'companyId', targetEntity: CompanySubscription::class)]
    private Collection $companySubscriptions;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->departments = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->timeLines = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->companySubscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(?string $about): static
    {
        $this->about = $about;

        return $this;
    }

    public function getEstablishedAt(): ?\DateTimeInterface
    {
        return $this->establishedAt;
    }

    public function setEstablishedAt(?\DateTimeInterface $establishedAt): static
    {
        $this->establishedAt = $establishedAt;

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

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setCompanyId($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getCompanyId() === $this) {
                $client->setCompanyId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Department>
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): static
    {
        if (!$this->departments->contains($department)) {
            $this->departments->add($department);
            $department->setCompanyId($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): static
    {
        if ($this->departments->removeElement($department)) {
            // set the owning side to null (unless already changed)
            if ($department->getCompanyId() === $this) {
                $department->setCompanyId(null);
            }
        }

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
            $request->setCompanyId($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getCompanyId() === $this) {
                $request->setCompanyId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TimeLine>
     */
    public function getTimeLines(): Collection
    {
        return $this->timeLines;
    }

    public function addTimeLine(TimeLine $timeLine): static
    {
        if (!$this->timeLines->contains($timeLine)) {
            $this->timeLines->add($timeLine);
            $timeLine->setCompanyId($this);
        }

        return $this;
    }

    public function removeTimeLine(TimeLine $timeLine): static
    {
        if ($this->timeLines->removeElement($timeLine)) {
            // set the owning side to null (unless already changed)
            if ($timeLine->getCompanyId() === $this) {
                $timeLine->setCompanyId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCompany($this);
        }
        return $this;
    }
  
    /**
     * @return Collection<int, Subscription>
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): static
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions->add($subscription);
            $subscription->addCompanyId($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
              }
        }
        return $this;
    }

    public function removeSubscription(Subscription $subscription): static
    {
        if ($this->subscriptions->removeElement($subscription)) {
            $subscription->removeCompanyId($this);
        }

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
            $companySubscription->setCompanyId($this);
        }

        return $this;
    }

    public function removeCompanySubscription(CompanySubscription $companySubscription): static
    {
        if ($this->companySubscriptions->removeElement($companySubscription)) {
            // set the owning side to null (unless already changed)
            if ($companySubscription->getCompanyId() === $this) {
                $companySubscription->setCompanyId(null);
            }
        }

        return $this;
    }
}
