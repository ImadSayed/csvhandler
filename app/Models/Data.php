<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
    /**
     * Process the CSV file and return the data.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public static function processCsv($file): array
    {
        //initialize data arrays
        $data = [];
        $responseData = [];

        // Call the readCsv method to get the data
        $response = CsvHandler::readCsv($file);

        if ($response['status_code'] === '400') {
            return $response; // Return error response if CSV reading failed
        }

        $data = $response['data']; // Get the data from the response
        $personFactory = new PersonFactory();

        //foreach cell in the CSV data, create Person objects
        foreach ($data as $row) {
            foreach ($row as $cell) {
                // Create Person objects from the cell string
                $persons = $personFactory->createPersonsFromString($cell);
                if (!empty($persons)) {
                    // $persons is an array of Person objects
                    $responseData = array_merge($responseData, $persons);
                }
            }
        }

        if (empty($responseData)) {
            return [
                'status_code' => '400',
                'name' => 'error',
                'message' => 'No valid person data found in the CSV file.',
                'data' => []
            ];
        }

        return [
            'status_code' => $response['status_code'],
            'name' => $response['name'],
            'message' => $response['message'],
            'persons' => $responseData,
        ];
    }
}
