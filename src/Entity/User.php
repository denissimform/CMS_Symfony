<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[UniqueEntity(fields: ['username'], message: 'Username already exists.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;
    public const GENDERS = [
        'Male',
        'Female',
        'Other'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('user:dt:read')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'You must provide a valid email address')]
    #[Groups('user:dt:read')]
    private ?string $email = null;
    
    #[ORM\Column]
    private array $roles = [];
    
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: 'You must provide a password')]
    #[Assert\Length(min: 5, minMessage: 'Minimum password length must be at least 5 characters')]
    private ?string $password = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank(message: 'You must provide a username')]
    #[Groups('user:dt:read')]
    private ?string $username = null;
    
    #[ORM\Column(length: 40)]
    #[Assert\NotBlank(message: 'You must provide a first name')]
    #[Groups('user:dt:read')]
    private ?string $firstName = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank(message: 'You must provide a last name')]
    #[Groups('user:dt:read')]
    private ?string $lastName = null;
    
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(message: 'You must select a gender')]
    #[Groups('user:dt:read')]
    private ?string $gender = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'You must provide your date of birth')]
    #[Groups('user:dt:read')]
    private ?\DateTimeInterface $dob = null;
    
    #[ORM\Column]
    #[Groups('user:dt:read')]
    private ?bool $isVerified = false;
    
    #[ORM\Column]
    #[Groups('user:dt:read')]
    private ?bool $isActive = true;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Client::class)]
    private Collection $clients;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Department::class)]
    private Collection $departments;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Project::class)]
    private Collection $projects;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Skills::class)]
    private Collection $skills;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: EmployeeSkills::class)]
    private Collection $employeeSkills;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Tasks::class)]
    private Collection $tasks;

    #[ORM\OneToMany(mappedBy: 'fromId', targetEntity: Request::class)]
    private Collection $requests;

    #[ORM\OneToMany(mappedBy: 'forwardTo', targetEntity: Request::class)]
    private Collection $forwardToRequests;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Timesheets::class)]
    private Collection $timesheets;

    #[ORM\OneToMany(mappedBy: 'empId', targetEntity: TimeLine::class)]
    private Collection $timeLines;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: ModesOfConversation::class)]
    private Collection $modesOfConversations;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: PasswordResetRequest::class)]
    private Collection $passwordResetRequests;

    #[ORM\OneToMany(mappedBy: 'createdBy', targetEntity: Bills::class)]
    private Collection $bills;

    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'You must specify the company name')]
    #[Groups('user:dt:read')]
    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Company $company = null;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->departments = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->employeeSkills = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->forwardToRequests = new ArrayCollection();
        $this->timesheets = new ArrayCollection();
        $this->timeLines = new ArrayCollection();
        $this->modesOfConversations = new ArrayCollection();
        $this->passwordResetRequests = new ArrayCollection();
        $this->bills = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function isAdmin(): bool
    {
        return in_array("ROLE_ADMIN", $this->roles);
    }

    public function isSuperAdmin(): bool
    {
        return in_array("ROLE_SUPER_ADMIN", $this->roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstName;
    }

    public function setFirstname(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        if (!in_array($gender, self::GENDERS))
            throw new \InvalidArgumentException(message: 'Invalid gender selected');
        $this->gender = $gender;

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(?\DateTimeInterface $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
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
            $client->setCreatedBy($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getCreatedBy() === $this) {
                $client->setCreatedBy(null);
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
            $department->setCreatedBy($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): static
    {
        if ($this->departments->removeElement($department)) {
            // set the owning side to null (unless already changed)
            if ($department->getCreatedBy() === $this) {
                $department->setCreatedBy(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, Contact>
    //  */
    // public function getContacts(): Collection
    // {
    //     return $this->contacts;
    // }

    // public function addContact(Contact $contact): static
    // {
    //     if (!$this->contacts->contains($contact)) {
    //         $this->contacts->add($contact);
    //         $contact->setReferenceId($this->getId());
    //     }

    //     return $this;
    // }

    // public function removeContact(Contact $contact): static
    // {
    //     if ($this->contacts->removeElement($contact)) {
    //         // set the owning side to null (unless already changed)
    //         if ($contact->getReferenceId() === $this) {
    //             $contact->setReferenceId(null);
    //         }
    //     }

    //     return $this;
    // }

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
            $project->setCreatedBy($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getCreatedBy() === $this) {
                $project->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Skills>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skills $skill): static
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->setCreatedBy($this);
        }

        return $this;
    }

    public function removeSkill(Skills $skill): static
    {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getCreatedBy() === $this) {
                $skill->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EmployeeSkills>
     */
    public function getEmployeeSkills(): Collection
    {
        return $this->employeeSkills;
    }

    public function addEmployeeSkill(EmployeeSkills $employeeSkill): static
    {
        if (!$this->employeeSkills->contains($employeeSkill)) {
            $this->employeeSkills->add($employeeSkill);
            $employeeSkill->setUserId($this);
        }

        return $this;
    }

    public function removeEmployeeSkill(EmployeeSkills $employeeSkill): static
    {
        if ($this->employeeSkills->removeElement($employeeSkill)) {
            // set the owning side to null (unless already changed)
            if ($employeeSkill->getUserId() === $this) {
                $employeeSkill->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tasks>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setUserId($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUserId() === $this) {
                $task->setUserId(null);
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
            $request->setFromId($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getFromId() === $this) {
                $request->setFromId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getForwardToRequests(): Collection
    {
        return $this->forwardToRequests;
    }

    public function addForwardToRequest(Request $forwardToRequest): static
    {
        if (!$this->forwardToRequests->contains($forwardToRequest)) {
            $this->forwardToRequests->add($forwardToRequest);
            $forwardToRequest->setForwardTo($this);
        }

        return $this;
    }

    public function removeForwardToRequest(Request $forwardToRequest): static
    {
        if ($this->forwardToRequests->removeElement($forwardToRequest)) {
            // set the owning side to null (unless already changed)
            if ($forwardToRequest->getForwardTo() === $this) {
                $forwardToRequest->setForwardTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Timesheets>
     */
    public function getTimesheets(): Collection
    {
        return $this->timesheets;
    }

    public function addTimesheet(Timesheets $timesheet): static
    {
        if (!$this->timesheets->contains($timesheet)) {
            $this->timesheets->add($timesheet);
            $timesheet->setUserId($this);
        }

        return $this;
    }

    public function removeTimesheet(Timesheets $timesheet): static
    {
        if ($this->timesheets->removeElement($timesheet)) {
            // set the owning side to null (unless already changed)
            if ($timesheet->getUserId() === $this) {
                $timesheet->setUserId(null);
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
            $timeLine->setEmpId($this);
        }

        return $this;
    }

    public function removeTimeLine(TimeLine $timeLine): static
    {
        if ($this->timeLines->removeElement($timeLine)) {
            // set the owning side to null (unless already changed)
            if ($timeLine->getEmpId() === $this) {
                $timeLine->setEmpId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModesOfConversation>
     */
    public function getModesOfConversations(): Collection
    {
        return $this->modesOfConversations;
    }

    public function addModesOfConversation(ModesOfConversation $modesOfConversation): static
    {
        if (!$this->modesOfConversations->contains($modesOfConversation)) {
            $this->modesOfConversations->add($modesOfConversation);
            $modesOfConversation->setCreatedBy($this);
        }

        return $this;
    }

    public function removeModesOfConversation(ModesOfConversation $modesOfConversation): static
    {
        if ($this->modesOfConversations->removeElement($modesOfConversation)) {
            // set the owning side to null (unless already changed)
            if ($modesOfConversation->getCreatedBy() === $this) {
                $modesOfConversation->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PasswordResetRequest>
     */
    public function getPasswordResetRequests(): Collection
    {
        return $this->passwordResetRequests;
    }

    public function addPasswordResetRequest(PasswordResetRequest $passwordResetRequest): static
    {
        if (!$this->passwordResetRequests->contains($passwordResetRequest)) {
            $this->passwordResetRequests->add($passwordResetRequest);
            $passwordResetRequest->setUserId($this);
        }

        return $this;
    }

    public function removePasswordResetRequest(PasswordResetRequest $passwordResetRequest): static
    {
        if ($this->passwordResetRequests->removeElement($passwordResetRequest)) {
            // set the owning side to null (unless already changed)
            if ($passwordResetRequest->getUserId() === $this) {
                $passwordResetRequest->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bills>
     */
    public function getBills(): Collection
    {
        return $this->bills;
    }

    public function addBill(Bills $bill): static
    {
        if (!$this->bills->contains($bill)) {
            $this->bills->add($bill);
            $bill->setCreatedBy($this);
        }

        return $this;
    }

    public function removeBill(Bills $bill): static
    {
        if ($this->bills->removeElement($bill)) {
            // set the owning side to null (unless already changed)
            if ($bill->getCreatedBy() === $this) {
                $bill->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }
}
