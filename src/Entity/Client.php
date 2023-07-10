<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    private ?string $name = null;

    #[ORM\Column(length: 60)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $about = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $isApproved = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?Company $companyId = null;

    #[ORM\ManyToOne(inversedBy: 'clients')]
    private ?User $createdBy = null;

    #[ORM\OneToMany(mappedBy: 'clientId', targetEntity: Project::class)]
    private Collection $projects;

    #[ORM\OneToMany(mappedBy: 'clientId', targetEntity: TimeLine::class)]
    private Collection $timeLines;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->timeLines = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(string $about): static
    {
        $this->about = $about;

        return $this;
    }

    public function getIsApproved(): ?int
    {
        return $this->isApproved;
    }

    public function setIsApproved(int $isApproved): static
    {
        $this->isApproved = $isApproved;

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
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setClientId($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getClientId() === $this) {
                $project->setClientId(null);
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
            $timeLine->setClientId($this);
        }

        return $this;
    }

    public function removeTimeLine(TimeLine $timeLine): static
    {
        if ($this->timeLines->removeElement($timeLine)) {
            // set the owning side to null (unless already changed)
            if ($timeLine->getClientId() === $this) {
                $timeLine->setClientId(null);
            }
        }

        return $this;
    }

}
