<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentsRepository::class)]
class Documents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $filename = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $referenceType = [];

    #[ORM\Column]
    private ?int $referenceId = null;

    #[ORM\ManyToOne(inversedBy: 'imagePath')]
    private ?ModesOfConversation $modesOfConversation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getReferenceType(): array
    {
        return $this->referenceType;
    }

    public function setReferenceType(array $referenceType): static
    {
        $this->referenceType = $referenceType;

        return $this;
    }

    public function getReferenceId(): ?int
    {
        return $this->referenceId;
    }

    public function setReferenceId(int $referenceId): static
    {
        $this->referenceId = $referenceId;

        return $this;
    }

    public function getModesOfConversation(): ?ModesOfConversation
    {
        return $this->modesOfConversation;
    }

    public function setModesOfConversation(?ModesOfConversation $modesOfConversation): static
    {
        $this->modesOfConversation = $modesOfConversation;

        return $this;
    }
}
