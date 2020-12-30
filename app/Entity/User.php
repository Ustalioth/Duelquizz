<?php

namespace App\Entity;

class User
{
    use HydrationTrait;

    protected ?int $id = null;

    protected ?string $email = null;

    protected ?string $password = null;

    protected ?string $firstName = null;

    protected ?string $lastName = null;

    protected ?string $points = null;

    protected ?string $registerAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    public function getRegisterAt()
    {
        return $this->registerAt;
    }

    public function setRegisterAt($registerAt)
    {
        $this->registerAt = $registerAt;

        return $this;
    }
}
