<?php

namespace App\Entity;

use App\Repository\MeasurentsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeasurentsRepository::class)
 */
class Measurents
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
    private $uuid;

    /**
     * @ORM\Column(type="integer")
     */
    private $co2value;


    /**
     * @ORM\Column(type="string")
     */
    private $sensorStatus;

    /**
     * @ORM\Column(type="string")
     */
    private $sensorCurrentStatus;

    /**
     * 
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

       
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCo2value(): ?int
    {
        return $this->co2value;
    }

    public function setCo2value(int $co2value): self
    {
        $this->co2value = $co2value;

        return $this;
    }


    public function getSensorStatus(): ?string
    {
        return $this->sensorStatus;
    }

    public function setSensorStatus(string $sensorStatus): self
    {
        $this->sensorStatus = $sensorStatus;

        return $this;
    }

    public function getSensorCurrentStatus(): ?string
    {
        return $this->sensorCurrentStatus;
    }

    public function setSensorCurrentStatus(string $sensorCurrentStatus): self
    {
        $this->sensorCurrentStatus = $sensorCurrentStatus;

        return $this;
    }

    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(DateTime $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }


}
