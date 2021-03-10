<?php

namespace App\Entity;

use App\Repository\StarshipsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StarshipsRepository::class)
 */
class Starships
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $starship_class;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $manufacturer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cost_in_credits;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $length;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $crew;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $passengers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $max_atmospherin_speed;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hyperdrive_rating;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $MGLT;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cargo_capacity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $consumables;

    /**
     * @ORM\Column(type="array")
     */
    private $films = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $edited;

    /**
     * @ORM\Column(type="array")
     */
    private $pilots = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

    public function getStarshipClass(): ?string
    {
        return $this->starship_class;
    }

    public function setStarshipClass(string $starship_class): self
    {
        $this->starship_class = $starship_class;

        return $this;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getCostInCredits(): ?string
    {
        return $this->cost_in_credits;
    }

    public function setCostInCredits(string $cost_in_credits): self
    {
        $this->cost_in_credits = $cost_in_credits;

        return $this;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(string $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getCrew(): ?string
    {
        return $this->crew;
    }

    public function setCrew(string $crew): self
    {
        $this->crew = $crew;

        return $this;
    }

    public function getPassengers(): ?string
    {
        return $this->passengers;
    }

    public function setPassengers(string $passengers): self
    {
        $this->passengers = $passengers;

        return $this;
    }

    public function getMaxAtmospherinSpeed(): ?string
    {
        return $this->max_atmospherin_speed;
    }

    public function setMaxAtmospherinSpeed(string $max_atmospherin_speed): self
    {
        $this->max_atmospherin_speed = $max_atmospherin_speed;

        return $this;
    }

    public function getHyperdriveRating(): ?string
    {
        return $this->hyperdrive_rating;
    }

    public function setHyperdriveRating(string $hyperdrive_rating): self
    {
        $this->hyperdrive_rating = $hyperdrive_rating;

        return $this;
    }

    public function getMGLT(): ?string
    {
        return $this->MGLT;
    }

    public function setMGLT(string $MGLT): self
    {
        $this->MGLT = $MGLT;

        return $this;
    }

    public function getCargoCapacity(): ?string
    {
        return $this->cargo_capacity;
    }

    public function setCargoCapacity(string $cargo_capacity): self
    {
        $this->cargo_capacity = $cargo_capacity;

        return $this;
    }

    public function getConsumables(): ?string
    {
        return $this->consumables;
    }

    public function setConsumables(string $consumables): self
    {
        $this->consumables = $consumables;

        return $this;
    }

    public function getFilms(): ?array
    {
        return $this->films;
    }

    public function setFilms(array $films): self
    {
        $this->films = $films;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function setCreated(string $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getEdited(): ?string
    {
        return $this->edited;
    }

    public function setEdited(string $edited): self
    {
        $this->edited = $edited;

        return $this;
    }

    public function getPilots(): ?array
    {
        return $this->pilots;
    }

    public function setPilots(array $pilots): self
    {
        $this->pilots = $pilots;

        return $this;
    }
}
