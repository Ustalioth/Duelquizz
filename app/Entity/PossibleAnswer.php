<?php

namespace App\Entity;

class PossibleAnswer
{
    use HydrationTrait;

    public ?int $id = null;

    protected ?int $question = null;

    protected ?string $label = null;

    protected ?int $correct = null;


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
     * Get the value of label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set the value of label
     *
     * @return  self
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }


    /**
     * Get the value of question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set the value of question
     *
     * @return  self
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get the value of correct
     */
    public function getCorrect()
    {
        return $this->correct;
    }

    /**
     * Set the value of correct
     *
     * @return  self
     */
    public function setCorrect($correct)
    {
        $this->correct = $correct;

        return $this;
    }
}
