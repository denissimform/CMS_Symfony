<?php

namespace App\Entity;

use App\Repository\TimelineProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimelineProjectRepository::class)]
class TimelineProject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'timelineProjects')]
    private ?TimeLine $timelineId = null;

    #[ORM\ManyToOne(inversedBy: 'timelineProjects')]
    private ?Project $projectId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimelineId(): ?TimeLine
    {
        return $this->timelineId;
    }

    public function setTimelineId(?TimeLine $timelineId): static
    {
        $this->timelineId = $timelineId;

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
}
