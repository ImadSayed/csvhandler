<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsvHandler extends Model
{
    /**
     * Read the CSV file and return the data.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    public static function readCsv(object $file): array
    {

        $fileExtension = $file->getClientOriginalExtension();
        if ($fileExtension !== 'csv') {
            $error = ['message' => 'Invalid file type. Please upload a CSV file.'];
            return
                [
                    'status_code' => '400',
                    'name' => 'error',
                    'message' => $error['message'],
                    'data' => []
                ];
        }

        // Logic to process the CSV file and return the data
        $data = []; // Processed data from the CSV file

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }

        return
            [
                'status_code' => '200',
                'name' => 'success',
                'message' => 'CSV file processed successfully',
                'data' => $data,
            ];
    }
}
