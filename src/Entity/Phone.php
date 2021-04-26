<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("get:products")
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("get:products")
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=13)
     * @Groups("get:products")
     */
    private $ean;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("get:products")
     */
    private $description;

    /**
     * @ORM\Column(type="decimal")
     * @Groups("get:products")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("get:products")
     */
    private $pictureUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("get:products")
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("get:products")
     */
    private $size;

    /**
     * @ORM\Column(type="date")
     * @Groups("get:products")
     */
    private $releaseDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getEan(): ?int
    {
        return $this->ean;
    }

    public function setEan(int $ean): self
    {
        $this->ean = $ean;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }
}
