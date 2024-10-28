<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $client = null;

    #[ORM\ManyToOne(inversedBy: 'ownerOrders')]
    private ?User $owner = null;


    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Delivery $delivery = null;

    #[ORM\ManyToOne]
    private ?Place $origin = null;

    #[ORM\ManyToOne]
    private ?Place $destination = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $amount_fuel = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): static
    {
        $this->client = $client;

        return $this;
    }


    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(?Delivery $delivery): static
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getOrigin(): ?Place
    {
        return $this->origin;
    }

    public function setOrigin(?Place $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    public function getDestination(): ?Place
    {
        return $this->destination;
    }

    public function setDestination(?Place $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getAmountFuel(): ?string
    {
        return $this->amount_fuel;
    }

    public function setAmountFuel(?string $amount_fuel): static
    {
        $this->amount_fuel = $amount_fuel;

        return $this;
    }


}
