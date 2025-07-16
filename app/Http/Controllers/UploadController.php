<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Data; // Data model where processing csv file processing begins

class UploadController extends Controller
{
    /**
     * Handle the file upload and process the CSV file.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Validate the uploaded file
        $request->validate([
            'csv' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        // Start measuring execution time
        // This will help in debugging and performance analysis
        // It is not necessary for the functionality of the code
        // but it can be useful to know how long the processing takes
        $executionStartTime = microtime(true);

        // Get the uploaded file
        if (!$request->hasFile('csv')) {
            return response()->json([
                'status_code' => '400',
                'name' => 'error',
                'message' => 'No file uploaded or file is not a valid CSV.',
                'data' => []
            ]);
        }

        if (!$request->file('csv')->isValid()) {
            return response()->json([
                'status_code' => '400',
                'name' => 'error',
                'message' => 'Uploaded file is not valid.',
                'data' => []
            ]);
        }

        // retrieve the uploaded file
        $file = $request->file('csv');

        // Call the Data model to process the CSV file
        $responseData = Data::processCsv($file);

        $responseData['returnedIn'] = (microtime(true) - $executionStartTime) / 1000 . " ms";

        return response()->json($responseData);
    }
}
