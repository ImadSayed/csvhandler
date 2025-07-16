<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Person;

class PersonFactory extends Model
{
    /**
     * List of potential titles that can be found in the CSV file.
     * This is used to identify titles in the CSV data.
     * The titles are case-insensitive, so we use strtolower() to compare them.
     */
    private const POTENTIAL_TITLES = [
        "Mr", "Mister", "Mrs", "Ms", "Dr", "Prof", "Miss", "Sir"
    ];

    /**
     * Creates a Person object from a CSV row.
     *
     * @param string $str A CSV row as a string.
     * @return array<Person> Returns an array of Person objects or an empty array if the row is invalid.
     */
    public function createPersonsFromString($str): array
    {
        // Check if the string is empty or does not contain any spaces
        // If it does not, we cannot extract titles, first names, and last names
        // Therefore, we return an empty array
        if (!$str || substr_count($str, ' ') === 0) {
            return [];
        }

        // Initialize arrays to hold Person objects and their attributes
        $persons = [];
        $titles = [];
        $initials = [];
        $firstNames = [];
        $lastNames = [];

        $potentialPersons = $this->splitStringIntoPersonStrings($str);

        //sort by length ascending
        usort($potentialPersons, function($a, $b) {
            return strlen((string)$a) - strlen((string)$b);
        });

        $personData = $this->extractPersonData($potentialPersons);
        extract($personData);

        // if there are more titles than last names, we assume that the last name is the same for all titles
        if (count($titles) !== count($lastNames)) {
            foreach ($titles as $title) {
                $person = new Person();
                $person->setTitle($title);
                $person->setInitial($initials[0] ?? null);
                $person->setFirstname($firstNames[0] ?? null);
                $person->setLastname($lastNames[0]);
                $persons[] = $person->toArray();
            }
            return $persons;
        }

        //otherwise, we create a Person object for each title, first name, initial and last name
        foreach ($titles as $index => $title) {
            $person = new Person();
            $person->setTitle($title);
            $person->setInitial($initials[$index] ?? null);
            $person->setFirstname($firstNames[$index] ?? null);
            $person->setLastname($lastNames[$index]);
            $persons[] = $person->toArray();
        }
        return $persons;
    }

    /**
     * Split the string into an array of potential person strings based on spaces as separators
     *
     * @param string $row A CSV row as a string.
     * @return Person[] An array of Person objects.
     */
    private function splitStringIntoPersonStrings(String $str): array
    {
        // Check for & or "and" separators and split the string accordingly to separate potential persons
        // This allows for more flexible parsing of names that may be separated by different conventions
        $str = trim($str);
        if (strpos($str, "&") !== false) {
            return explode("&", $str);
        } elseif (strpos($str, "and") !== false) {
            return explode("and", $str);
        } else {
            return [$str];
        }
    }

    /**
     * Extracts titles, initials, first names, and last names from an array of potential persons.
     *
     * @param array $potentialPersons An array of potential person strings.
     * @return array An associative array containing titles, initials, first names, and last names.
     */
    private function extractPersonData(array $potentialPersons): array
    {
        // Initialize arrays to hold titles, initials, first names, and last names
        $titles = [];
        $initials = [];
        $firstNames = [];
        $lastNames = [];

        // If no potential persons are found, return empty arrays
        // This prevents unnecessary processing and avoids errors in the loop below
        if (empty($potentialPersons)) {
            return [
                'titles' => $titles,
                'initials' => $initials,
                'firstNames' => $firstNames,
                'lastNames' => $lastNames
            ];
        }

        // Loop through each potential person string and extract titles, initials, first names, and last names
        // We assume that the first word is a title, and the last word is a last name.
        foreach ($potentialPersons as $personString) {
            $personString = trim((string)$personString);
            $personString = str_replace(".", "", $personString);//remove any dots from the string

            if (in_array(ucfirst(strtolower($personString)), self::POTENTIAL_TITLES)) {
                //only a title exists in this string
                $titles[] = $personString;
                continue;
            }

            $titles[] = substr($personString, 0, strpos($personString, ' ')); //we assume the first word is a title
            $personString = substr($personString, strpos($personString, ' ') + 1); //remove the title from the string
            $numberOfSpaces = substr_count($personString, ' ');

            if ($numberOfSpaces === 1) {
                //only a first name or initial and a last name exist in this string
                $names = explode(' ', $personString);

                if (strlen($names[0]) == 1) {
                    $initials[] = $names[0];
                } else {
                    $firstNames[] = $names[0];
                }
                $lastNames[] = $names[1];
            } elseif ($numberOfSpaces === 0) {
                //only a last name exists in this string
                $lastNames[] = $personString;
                $firstNames[] = null;
                $initials[] = null;

            }
        }

        return [
            'titles' => $titles ?? [],
            'initials' => $initials ?? [],
            'firstNames' => $firstNames ?? [],
            'lastNames' => $lastNames ?? []
        ];
    }
}

