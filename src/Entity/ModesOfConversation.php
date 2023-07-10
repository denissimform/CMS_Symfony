<?php

namespace App\Entity;

use App\Repository\ModesOfConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModesOfConversationRepository::class)]
class ModesOfConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'modesOfConversations')]
    private ?User $createdBy = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'mode', targetEntity: TimeLine::class)]
    private Collection $timeLines;

    #[ORM\OneToMany(mappedBy: 'modesOfConversation', targetEntity: Documents::class)]
    private Collection $imagePath;

    public function __construct()
    {
        $this->timeLines = new ArrayCollection();
        $this->imagePath = new ArrayCollection();
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

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
            $timeLine->setMode($this);
        }

        return $this;
    }

    public function removeTimeLine(TimeLine $timeLine): static
    {
        if ($this->timeLines->removeElement($timeLine)) {
            // set the owning side to null (unless already changed)
            if ($timeLine->getMode() === $this) {
                $timeLine->setMode(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Documents>
     */
    public function getImagePath(): Collection
    {
        return $this->imagePath;
    }

    public function addImagePath(Documents $imagePath): static
    {
        if (!$this->imagePath->contains($imagePath)) {
            $this->imagePath->add($imagePath);
            $imagePath->setModesOfConversation($this);
        }

        return $this;
    }

    public function removeImagePath(Documents $imagePath): static
    {
        if ($this->imagePath->removeElement($imagePath)) {
            // set the owning side to null (unless already changed)
            if ($imagePath->getModesOfConversation() === $this) {
                $imagePath->setModesOfConversation(null);
            }
        }

        return $this;
    }
}
