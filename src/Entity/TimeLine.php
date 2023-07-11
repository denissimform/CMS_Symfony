<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TimeLineRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: TimeLineRepository::class)]
class TimeLine
{
    use TimestampableEntity;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $decription = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $conclusion = null;

    #[ORM\ManyToOne(inversedBy: 'timeLines')]
    private ?Client $clientId = null;

    #[ORM\ManyToOne(inversedBy: 'timeLines')]
    private ?User $empId = null;

    #[ORM\ManyToOne(inversedBy: 'timeLines')]
    private ?Company $companyId = null;

    #[ORM\ManyToOne(inversedBy: 'timeLines')]
    private ?ModesOfConversation $mode = null;

    #[ORM\OneToMany(mappedBy: 'timelineId', targetEntity: TimelineProject::class)]
    private Collection $timelineProjects;

    public function __construct()
    {
        $this->timelineProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDecription(): ?string
    {
        return $this->decription;
    }

    public function setDecription(string $decription): static
    {
        $this->decription = $decription;

        return $this;
    }

    public function getConclusion(): ?string
    {
        return $this->conclusion;
    }

    public function setConclusion(string $conclusion): static
    {
        $this->conclusion = $conclusion;

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

    public function getEmpId(): ?User
    {
        return $this->empId;
    }

    public function setEmpId(?User $empId): static
    {
        $this->empId = $empId;

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

    public function getMode(): ?ModesOfConversation
    {
        return $this->mode;
    }

    public function setMode(?ModesOfConversation $mode): static
    {
        $this->mode = $mode;

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
            $timelineProject->setTimelineId($this);
        }

        return $this;
    }

    public function removeTimelineProject(TimelineProject $timelineProject): static
    {
        if ($this->timelineProjects->removeElement($timelineProject)) {
            // set the owning side to null (unless already changed)
            if ($timelineProject->getTimelineId() === $this) {
                $timelineProject->setTimelineId(null);
            }
        }

        return $this;
    }
}
