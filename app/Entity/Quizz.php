<?php

namespace App\Entity;

class Quizz{
    use HydrationTrait;

    public ?int $id = null;

    protected ?string $user1 = null;

    protected ?string $user2 = null;

    protected ?string $mode = null;

    protected ?string $startAt = null;

    protected ?string $winner = null;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of user1
     */ 
    public function getUser1()
    {
        return $this->user1;
    }

    /**
     * Set the value of user1
     *
     * @return  self
     */ 
    public function setUser1($user1)
    {
        $this->user1 = $user1;

        return $this;
    }

    /**
     * Get the value of user2
     */ 
    public function getUser2()
    {
        return $this->user2;
    }

    /**
     * Set the value of user2
     *
     * @return  self
     */ 
    public function setUser2($user2)
    {
        $this->user2 = $user2;

        return $this;
    }

    /**
     * Get the value of mode
     */ 
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the value of mode
     *
     * @return  self
     */ 
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * Get the value of startAt
     */ 
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set the value of startAt
     *
     * @return  self
     */ 
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Get the value of winner
     */ 
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * Set the value of winner
     *
     * @return  self
     */ 
    public function setWinner($winner)
    {
        $this->winner = $winner;

        return $this;
    }
}