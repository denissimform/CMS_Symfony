<?php

namespace App\Entity;

use App\Enum\UserType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactRepository;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    use TimestampableEntity;

    public const USER_TYPE = ['User', 'Company', 'Client'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $contactNo = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 90)]
    private ?string $city = null;

    #[ORM\Column(length: 90)]
    private ?string $state = null;

    #[ORM\Column(length: 6)]
    private ?string $pinCode = null;

    #[ORM\Column(length: 90)]
    private ?string $country = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $usertype = null;

    #[ORM\Column]
    private ?int $referenceId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContactNo(): ?string
    {
        return $this->contactNo;
    }

    public function setContactNo(string $contactNo): static
    {
        $this->contactNo = $contactNo;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getPinCode(): ?string
    {
        return $this->pinCode;
    }

    public function setPinCode(string $pinCode): static
    {
        $this->pinCode = $pinCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getUsertype(): ?string
    {
        return $this->usertype;
    }

    public function setUsertype(?string $usertype): static
    {
        if (!in_array($usertype, self::USER_TYPE))
            throw new \InvalidArgumentException("Invalid value passed!");

        $this->usertype = $usertype;

        return $this;
    }

    public function getReferenceId(): ?int
    {
        return $this->referenceId;
    }

    public function setReferenceId(?int $ReferenceId): static
    {
        $this->referenceId = $ReferenceId;

        return $this;
    }
}
