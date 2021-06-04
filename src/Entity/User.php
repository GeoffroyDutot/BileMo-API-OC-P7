<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @OA\Property(type="integer", description="The user's id.")
     * @Groups("get:users")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @OA\Property(type="string", description="The user's firstname.")
     * @Groups("get:users")
     * @Groups("write:users")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @OA\Property(type="string", description="The user's lastname.")
     * @Groups("get:users")
     * @Groups("write:users")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @OA\Property(type="string", description="The user's email.")
     * @Groups("get:users")
     * @Groups("write:users")
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
     * @OA\Property(type="string",format="date-time", description="The user's registration date.")
     * @Groups("get:users")
     * @Groups("write:users")
     */
    private $dateRegistration;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="users")
     * @OA\Property(type="object", description="The user's company.")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateRegistration(): ?\DateTimeInterface
    {
        return $this->dateRegistration;
    }

    public function setDateRegistration(\DateTimeInterface $dateRegistration): self
    {
        $this->dateRegistration = $dateRegistration;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
