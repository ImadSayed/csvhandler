<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\PersonFactory;

class PersonFactoryUnitTest extends TestCase
{
    /**
     * Test if a PersonFactory can not create a person from an empty string
     */
    public function test_person_factory_cannot_create_a_person_from_empty_string(): void
    {
        $personFactory = new PersonFactory();
        $result = $personFactory->createPersonsFromString('');
        $this->assertEmpty($result);
    }

    /**
     * Test if a PersonFactory can not create a person from a string with a title not in potential titles
     */
    public function test_person_factory_cannot_create_a_person_from_string_with_invalid_title(): void
    {
        $personFactory = new PersonFactory();
        $result = $personFactory->createPersonsFromString('InvalidTitle John Doe');
        $this->assertEmpty($result);
    }

    /**
     * Test if a person factory can create a person without a first name or initial 
     */
    public function test_person_factory_can_create_a_person_without_first_name_or_initial(): void
    {
        $personFactory = new PersonFactory();
        $result = $personFactory->createPersonsFromString('Mr Doe');

        $expected = [
            [
                'title' => "Mr",
                'first_name' => null,
                'last_name' => "Doe",
                'initial' => null
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that two person objects are created and returned in an array from a string with multiple titles
     */
    public function test_person_factory_can_create_multiple_persons_from_string_with_multiple_titles(): void
    {
        $personFactory = new PersonFactory();
        $result = $personFactory->createPersonsFromString('Mr John Doe & Dr J Smith');

        $expected = [
            [
                'title' => "Dr",
                'first_name' => null,
                'last_name' => "Smith",
                'initial' => "J"
            ],
            [
                'title' => "Mr",
                'first_name' => "John",
                'last_name' => "Doe",
                'initial' => null
            ],
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * Test that two person objects are created and returned in one array from a string that uses only 1 last name
     */
    public function test_person_factory_can_create_multiple_persons_from_string_with_one_last_name(): void
    {
        $personFactory = new PersonFactory();
        $result = $personFactory->createPersonsFromString('Mr & Mrs Smith');

        $expected = [
            [
                'title' => "Mr",
                'first_name' => null,
                'last_name' => "Smith",
                'initial' => null
            ],
            [
                'title' => "Mrs",
                'first_name' => null,
                'last_name' => "Smith",
                'initial' => null
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}