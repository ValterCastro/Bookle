<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;


class SearchController
{
    public function search(Request $request)
    {


        // $request->validate([
        //     'title' => 'required|string',
        // ]);

        Log::info("hey");


        // $title = $request->input('title');

        $client = new Client();

        $datasetSlug = 'pypiahmad/goodreads-book-reviews1';
        $url = "https://www.kaggle.com/api/v1/datasets/download/$datasetSlug";

        $headers = [
            'Authorization' => 'Bearer ' . env('KAGGLE_KEY')

        ];

        Log::info('Entering search method');
        Log::info('Request URL and Headers', [
            'url' => $url,
            'headers' => $headers,
        ]);


        try {
            $response = $client->get($url, [
                'headers' => $headers,
                'stream' => true,
            ]);


            Log::info('Kaggle API Response', [
                'status' => $response->getStatusCode(),
                'body' => $response->getBody()->getContents(),
            ]);

            $filePath = storage_path('app/goodreads_books.zip');

            $fp = fopen($filePath, 'w');

            while (!$response->getBody()->eof()) {
                fwrite($fp, $response->getBody()->read(1024));
            }

            fclose($fp);

            return response()->json(['message' => 'Dataset Download Successfully.', 'file' => $filePath]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {

            Log::error('Guzzle Request Exception', [
                'message' => $e->getMessage(),
                'request' => [
                    'url' => $url,
                    'headers' => $headers,
                ],
                'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null,
            ]);
            return response()->json(['error' => 'Request failed. ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showFirstTitle(Request $request)
    {

        $filePath = storage_path('app/goodreads_books.json');

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            return response()->json(['error' => 'Unable to open file.'], 500);
        }


        $books = [];
        $lineCount = 0; // Count the number of lines read

        // Read up to a certain number of lines (e.g., 5 lines)
        while (($line = fgets($handle)) !== false && $lineCount < 10) {
            // Decode each line
            $data = json_decode($line, true);

            // Check if data is an array and contains the title
            if (is_array($data) && isset($data['title'])) {
                $books[] = $data;
                $lineCount++;
            }
        }

        fclose($handle);

        // Decode the first line
        if (count($books) > 0) {
            // Store titles in the session
            session(['books' => $books]);
    
            // Redirect to the search results view
            return redirect()->route('search_results');
        } else {
            return response()->json(['error' => 'No books found.'], 404);
        }
    }
}
