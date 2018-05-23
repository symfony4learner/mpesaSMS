<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CodeRepository")
 */
class Code
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $random_code;

    /**
     * @ORM\Column(type="string", length=7)
     */
    private $status;

    public function getId()
    {
        return $this->id;
    }

    public function getRandomCode(): ?string
    {
        return $this->random_code;
    }

    public function setRandomCode(string $random_code): self
    {
        $this->random_code = $random_code;

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
