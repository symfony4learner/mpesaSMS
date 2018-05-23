<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SMSInRepository")
 */
class SMSIn
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $confirmation_code;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $client_name;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $amount_received;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $balance;

    /**
     * @ORM\Column(type="datetime")
     */
    private $received_on;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $sms_origin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $whole_sms;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $status;

    private $service_fee;
    private $expected_sms_origin;
    private $example_message;

    public function getId()
    {
        return $this->id;
    }


    public function getConfirmationCode(): ?string
    {
        return $this->confirmation_code;
    }

    public function setConfirmationCode(string $confirmation_code): self
    {
        $this->confirmation_code = $confirmation_code;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): self
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getAmountReceived(): ?string
    {
        return $this->amount_received;
    }

    public function setAmountReceived(string $amount_received): self
    {
        $this->amount_received = $amount_received;

        return $this;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getReceivedOn(): ?\DateTimeInterface
    {
        return $this->received_on;
    }

    public function setReceivedOn(\DateTimeInterface $received_on): self
    {
        $this->received_on = $received_on;

        return $this;
    }

    public function getSmsOrigin(): ?string
    {
        return $this->sms_origin;
    }

    public function setSmsOrigin(string $sms_origin): self
    {
        $this->sms_origin = $sms_origin;

        return $this;
    }

    public function getWholeSms(): ?string
    {
        return $this->whole_sms;
    }

    public function setWholeSms(string $whole_sms): self
    {
        $this->whole_sms = $whole_sms;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
