<?php

namespace App\Models;

class Person
{
    /** @var string */
    private $title;

    /** @var string|null */
    private $first_name;

    /** @var string */
    private $last_name;

    /** @var string|null */
    private $initial;

    /** @return string*/
    public function getTitle(): string
    {
        return $this->title;
    }

    /** @return string|null */
    public function getFirstname(): ?string
    {
        return $this->first_name;
    }

    /** @return string */
    public function getLastname(): string
    {
        return $this->last_name;
    }

    /** @return string|null */
    public function getInitial(): ?string
    {
        return $this->initial;
    }

    /**
     * Set the title of the person.
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Set the first name of the person.
     *
     * @param string|null $firstname
     * @return void
     */
    public function setFirstname(?string $firstname): void
    {
        $this->first_name = $firstname;
    }

    /**
     * Set the last name of the person.
     *
     * @param string $lastname
     * @return void
     */
    public function setLastname(string $lastname): void
    {
        $this->last_name = $lastname;
    }

    /**
     * Set the initial of the person.
     *
     * @param string|null $initial
     * @return void
     */
    public function setInitial(?string $initial): void
    {
        $this->initial = $initial;
    }

    /**
     * Convert the Person object to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        // Return an associative array representation of the Person object
        // This is useful for returning data in a structured format, such as in API responses
        return [
            'title' => $this->getTitle(),
            'first_name' => $this->getFirstname(),
            'last_name' => $this->getLastname(),
            'initial' => $this->getInitial(),
        ];
    }
}
