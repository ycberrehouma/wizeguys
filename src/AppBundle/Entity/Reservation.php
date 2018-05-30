<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=40)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="email_address", type="string", length=255, nullable=true)
     */
    private $emailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=10)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="guest_number", type="string", length=2)
     */
    private $guestNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=25)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", length=25)
     */
    private $time;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return Reservation
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set emailAddress
     *
     * @param string $emailAddress
     *
     * @return Reservation
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Reservation
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set guestNumber
     *
     * @param string $guestNumber
     *
     * @return Reservation
     */
    public function setGuestNumber($guestNumber)
    {
        $this->guestNumber = $guestNumber;

        return $this;
    }

    /**
     * Get guestNumber
     *
     * @return string
     */
    public function getGuestNumber()
    {
        return $this->guestNumber;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Reservation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }


    /**
     * Set time
     *
     * @param string $time
     *
     * @return Reservation
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }


    /**
     * Get time
     *
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }
}
