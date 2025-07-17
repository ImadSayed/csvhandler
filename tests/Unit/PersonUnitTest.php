<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Person;

class PersonUnitTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    /**
     * test a person object can be created and converted to an array.
     */
    public function test_can_return_person_object_as_array(): void
    {
        $person = new Person();
        $person->setTitle("Mr");
        $person->setFirstname("John");
        $person->setLastname("Doe");
        $person->setInitial("J");

        $expected = [
            'title' => "Mr",
            'first_name' => "John",
            'last_name' => "Doe",
            'initial' => "J"
        ];

        $this->assertEquals($expected, $person->toArray());
    }

    /**
     * test a person object can be created with an omitted initial.
     */
    public function test_can_create_a_person_object_with_omitted_initial(): void
    {
        $person = new Person();
        $person->setTitle("Dr");
        $person->setFirstname("Jane");
        $person->setLastname("Smith");

        $expected = [
            'title' => "Dr",
            'first_name' => "Jane",
            'last_name' => "Smith",
            'initial' => null
        ];

        $this->assertEquals($expected, $person->toArray());
    }

    /**
     * test a person object can be created with an omitted firstname.
     */
    public function test_can_create_a_person_object_with_omitted_firstname(): void
    {
        $person = new Person();
        $person->setTitle("Ms");
        $person->setLastname("Johnson");

        $expected = [
            'title' => "Ms",
            'first_name' => null,
            'last_name' => "Johnson",
            'initial' => null
        ];

        $this->assertEquals($expected, $person->toArray());
    }
}
