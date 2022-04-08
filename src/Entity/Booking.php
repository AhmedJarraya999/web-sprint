<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $bookingDate;

    /**
     * @ORM\Column(type="date")
     */
    private $firstDate;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;



    /**
     * @ORM\ManyToOne(targetEntity=Stay::class, inversedBy="bookings")
     */
    private $stay;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookingDate(): ?\DateTimeInterface
    {
        return $this->bookingDate;
    }

    public function setBookingDate(\DateTimeInterface $bookingDate): self
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    public function getFirstDate(): ?\DateTimeInterface
    {
        return $this->firstDate;
    }

    public function setFirstDate(\DateTimeInterface $firstDate): self
    {
        $this->firstDate = $firstDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getStay(): ?Stay
    {
        return $this->stay;
    }

    public function setStay(?Stay $stay): self
    {
        $this->stay = $stay;

        return $this;
    }
}
